<?php

namespace App\Http\Controllers;

use App\Models\TransaksiMaterial;
use App\Models\VerifikasiTraksaksi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $data = Auth::user();
        $breadcrumb = (object) [
            'list' => ['Dashboard', '']
        ];
        $total_transaksi = TransaksiMaterial::with(['dibuat_oleh', 'detail_transaksi', 'verifikasi_transaksi'])->where('delet_at', '0')->count();
        $disetujui = VerifikasiTraksaksi::with(['transaksi_material', 'diverifikasi_oleh'])
            ->where('status', '1')
            ->whereHas('transaksi_material', function ($q) {
                $q->where('delet_at', '0');
            })
            ->count();

        $dikembalikan = VerifikasiTraksaksi::with(['transaksi_material', 'diverifikasi_oleh'])
            ->where('status', '3')
            ->whereHas('transaksi_material', function ($q) {
                $q->where('delet_at', '0');
            })
            ->count();

        $menunggu = VerifikasiTraksaksi::with(['transaksi_material', 'diverifikasi_oleh'])
            ->where('status', '0')
            ->whereHas('transaksi_material', function ($q) {
                $q->where('delet_at', '0');
            })
            ->count();

        $total_transaksi_pen = TransaksiMaterial::with(['dibuat_oleh', 'detail_transaksi', 'verifikasi_transaksi'])
            ->where('delet_at', '0')
            ->where('jenis', '0')
            ->count();

        $total_transaksi_kel = TransaksiMaterial::with(['dibuat_oleh', 'detail_transaksi', 'verifikasi_transaksi'])
            ->where('delet_at', '0')
            ->where('jenis', '1')
            ->count();

        $transaksi = TransaksiMaterial::with(['dibuat_oleh', 'detail_transaksi', 'verifikasi_transaksi'])->where('delet_at', '0')->get();
        $jenisList = TransaksiMaterial::distinct()
            ->pluck('jenis')
            ->mapWithKeys(fn($j) => [$j => match ((int)$j) {
                1 => 'Pengeluaran',
                0 => 'Penerimaan',
                default => "Lainnya ($j)"
            }]);

        $grouped = $transaksi->groupBy(function ($item) {
            return Carbon::parse($item->created_at)->format('o-W');
        })->map(function ($items) use ($jenisList) {

            $perJenis = [];

            foreach ($jenisList as $jenisValue => $label) {
                $perJenis[$jenisValue] = $items->where('jenis', $jenisValue)->count();
            }

            return $perJenis;
        });

        $labelsMingguan = $grouped->keys()->map(function ($key) {
            [$tahun, $minggu] = explode('-', $key);
            return "Minggu $minggu, $tahun";
        });
        $series = [];

        foreach ($jenisList as $jenisValue => $label) {
            $chartData = $grouped->map(fn($week) => $week[$jenisValue] ?? 0)->values();

            $series[] = [
                'name' => $label,
                'data' => $chartData
            ];
        }


        return view('pages.dashboard.index', [
            'data' => $data,
            'breadcrumb' => $breadcrumb,
            'total_transaksi' => $total_transaksi,
            'total_transaksi_pen' => $total_transaksi_pen,
            'total_transaksi_kel' => $total_transaksi_kel,
            'disetujui' => $disetujui,
            'dikembalikan' => $dikembalikan,
            'menunggu' => $menunggu,
            'labelsMingguan' => $labelsMingguan,
            'seriesChart' => $series
        ]);
    }
}
