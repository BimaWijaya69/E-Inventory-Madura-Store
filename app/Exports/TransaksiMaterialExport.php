<?php

namespace App\Exports;

use App\Models\TransaksiMaterial;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TransaksiMaterialExport implements FromArray, WithHeadings
{
    protected $jenis;
    protected $status;
    protected $data;

    public function __construct($jenis = null, $status = null)
    {
        $this->jenis = $jenis;
        $this->status = $status;
        $this->data = Auth::user();
    }

    public function headings(): array
    {
        return [
            'Kode Transaksi',
            'Tanggal',
            'Jenis',
            'Nama Pihak Transaksi',
            'Keperluan',
            'Nomor Pelanggan',
            'Nama Material',
            'Jumlah'
        ];
    }

    public function array(): array
    {
        $query = TransaksiMaterial::with(['detail_transaksi', 'verifikasi_transaksi'])
            ->where('delet_at', '0');

        if ((int) $this->data->role !== '1') {
            $roleLogin = (int) $this->data->role;

            $query->whereHas('dibuat_oleh', function ($q) use ($roleLogin) {
                $q->where('role', $roleLogin);
            });
        }

        if ($this->jenis !== null && $this->jenis !== '') {
            $query->where('jenis', $this->jenis);
        }

        if ($this->status !== null && $this->status !== '') {
            $query->whereHas('verifikasi_transaksi', function ($q) {
                $q->where('status', $this->status);
            });
        }

        $data = [];
        $transaksi = $query->get();

        foreach ($transaksi as $t) {
            foreach ($t->detail_transaksi as $index => $d) {

                $data[] = [
                    $index == 0 ? $t->kode_transaksi : '',
                    $index == 0 ? $t->tanggal : '',
                    $index == 0 ? ($t->jenis == '0' ? 'Penerimaan' : 'Pengeluaran') : '',
                    $index == 0 ? $t->nama_pihak_transaksi : '',
                    $index == 0 ? $t->keperluan : '',
                    $index == 0 ? $t->nomor_pelanggan : '',
                    $d->material->nama_material ?? '-',
                    $d->jumlah
                ];
            }
        }

        return $data;
    }
}
