<?php

namespace App\Http\Controllers;

use App\Models\DetailTransaksiMaterial;
use App\Models\Material;
use App\Models\TransaksiMaterial;
use App\Models\VerifikasiTraksaksi;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TransaksiMaterialController extends Controller
{
    protected $data;
    public function __construct()
    {
        $this->data = Auth::user();
    }

    public function materialMasukView()
    {
        $breadcrumb = (object) [
            'list' => ['Materaial Masuk', '']
        ];
        return view('pages.transaksi.material-masuk',  ['data' => $this->data, 'breadcrumb' => $breadcrumb]);
    }

    public function materialKeluarView()
    {
        $transaksis = TransaksiMaterial::with(['dibuat_oleh', 'detail_transaksi', 'verifikasi_transaksi'])->where('jenis', '1')->get();
        $breadcrumb = (object) [
            'list' => ['Material Keluar', '']
        ];
        return view('pages.transaksi.material-keluar.index',  ['data' => $this->data, 'breadcrumb' => $breadcrumb, 'transaksis' => $transaksis]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function createMaterialKeluarView()
    {
        $kode = $this->generateKode('1');
        $materials = Material::where('delet_at', '0')->get();
        $breadcrumb = (object) [
            'list' => ['Material Keluar', 'Tambah']
        ];
        return view('pages.transaksi.material-keluar.create-transaksi-material-kel',  ['data' => $this->data, 'breadcrumb' => $breadcrumb, 'materials' => $materials, 'kode' => $kode]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $data = [
                'kode_transaksi'       => $request->kode_transaksi,
                'tanggal'              => $request->tanggal,
                'jenis'                => $request->jenis,
                'nama_pihak_transaksi' => $request->nama_pihak_transaksi,
                'keperluan'            => $request->keperluan,
                'nomor_pelanggan'      => $request->nomor_pelanggan,
                'dibuat_oleh'          => Auth::id(),
            ];

            if ($request->hasFile('foto_bukti')) {
                $data['foto_bukti'] = $request->file('foto_bukti')->store('bukti_transaksi', 'public');
            }

            if ($request->jenis == '1') {
                if ($request->hasFile('foto_sr_sebelum')) {
                    $data['foto_sr_sebelum'] = $request->file('foto_sr_sebelum')->store('foto_sr', 'public');
                }
                if ($request->hasFile('foto_sr_sesudah')) {
                    $data['foto_sr_sesudah'] = $request->file('foto_sr_sesudah')->store('foto_sr', 'public');
                }
            } else {
                $data['foto_sr_sebelum'] = null;
                $data['foto_sr_sesudah'] = null;
            }

            $transaksi = TransaksiMaterial::create($data);
            if ($request->has('materials')) {
                foreach ($request->materials as $item) {
                    $material = Material::findOrFail($item['id']);
                    if ($request->jenis == '1') {
                        if ($material->stok < $item['jumlah']) {
                            throw new Exception("Stok '{$material->nama_material}' tidak mencukupi. Sisa: {$material->stok}, Diminta: {$item['jumlah']}");
                        }
                        $material->decrement('stok', $item['jumlah']);
                    } else {
                        $material->increment('stok', $item['jumlah']);
                    }
                    DetailTransaksiMaterial::create([
                        'transaksi_id' => $transaksi->id,
                        'material_id'  => $item['id'],
                        'jumlah'       => $item['jumlah'],
                    ]);
                }
            }

            VerifikasiTraksaksi::create([
                'transaksi_id' => $transaksi->id,
                'status' => $this->data->role == '1' ? '1' : '0',
                'diverifikasi_oleh' => $this->data->role == '1' ? $this->data->id : null
            ]);

            DB::commit();
            $suffixRoute = ($request->jenis == 1) ? 'keluars' : 'masuks';

            return response()->json([
                'success'  => true,
                'message'  => 'Data transaksi berhasil disimpan!',
                'redirect' => route('material-' . $suffixRoute)
            ], 200);
        } catch (Exception $e) {
            DB::rollBack();
            if (isset($data['foto_bukti'])) Storage::disk('public')->delete($data['foto_bukti']);
            if (isset($data['foto_sr_sebelum'])) Storage::disk('public')->delete($data['foto_sr_sebelum']);
            if (isset($data['foto_sr_sesudah'])) Storage::disk('public')->delete($data['foto_sr_sesudah']);
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function editMaterialKeluarView(string $id)
    {
        $breadcrumb = (object) [
            'list' => ['Material Keluar', 'Update']
        ];
        $materials = Material::where('delet_at', '0')->get();
        $data = $this->data;
        $t = TransaksiMaterial::with(['dibuat_oleh', 'detail_transaksi', 'verifikasi_transaksi'])->where('id', $id)->firstOrFail();
        return view('pages.transaksi.material-keluar.edit-transaksi-material-kel', compact('t', 'breadcrumb', 'materials', 'data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $transaksi = TransaksiMaterial::findOrFail($id);

        if (!$request->has('materials') || count($request->materials) == 0) {
            return response()->json(['success' => false, 'message' => 'Material tidak boleh kosong!'], 422);
        }

        DB::beginTransaction();
        try {
            $oldDetails = $transaksi->detail_transaksi;

            foreach ($oldDetails as $oldItem) {
                $material = Material::find($oldItem->material_id);

                if ($material) {
                    if ($transaksi->jenis == '1') {
                        $material->increment('stok', $oldItem->jumlah);
                    } else {
                        $material->decrement('stok', $oldItem->jumlah);
                    }
                }
            }
            $transaksi->detail_transaksi()->delete();
            $data = [
                'tanggal'              => $request->tanggal,
                'jenis'                => $request->jenis,
                'nama_pihak_transaksi' => $request->nama_pihak_transaksi,
                'keperluan'            => $request->keperluan,
                'nomor_pelanggan'      => $request->nomor_pelanggan,
            ];
            if ($request->hasFile('foto_bukti')) {
                if ($transaksi->foto_bukti) Storage::disk('public')->delete($transaksi->foto_bukti);
                $data['foto_bukti'] = $request->file('foto_bukti')->store('bukti_transaksi', 'public');
            }

            if ($request->jenis == '1') {
                if ($request->hasFile('foto_sr_sebelum')) {
                    if ($transaksi->foto_sr_sebelum) Storage::disk('public')->delete($transaksi->foto_sr_sebelum);
                    $data['foto_sr_sebelum'] = $request->file('foto_sr_sebelum')->store('foto_sr', 'public');
                }
                if ($request->hasFile('foto_sr_sesudah')) {
                    if ($transaksi->foto_sr_sesudah) Storage::disk('public')->delete($transaksi->foto_sr_sesudah);
                    $data['foto_sr_sesudah'] = $request->file('foto_sr_sesudah')->store('foto_sr', 'public');
                }
            } else {
                if ($transaksi->foto_sr_sebelum) Storage::disk('public')->delete($transaksi->foto_sr_sebelum);
                if ($transaksi->foto_sr_sesudah) Storage::disk('public')->delete($transaksi->foto_sr_sesudah);
                $data['foto_sr_sebelum'] = null;
                $data['foto_sr_sesudah'] = null;
            }

            $transaksi->update($data);
            foreach ($request->materials as $newItem) {
                $material = Material::findOrFail($newItem['id']);
                $qtyBaru  = $newItem['jumlah'];

                if ($request->jenis == '1') {
                    if ($material->stok < $qtyBaru) {
                        throw new Exception("Stok '{$material->nama_material}' tidak cukup! Tersedia: {$material->stok}, Diminta: {$qtyBaru}");
                    }

                    $material->decrement('stok', $qtyBaru);
                } else {
                    $material->increment('stok', $qtyBaru);
                }
                DetailTransaksiMaterial::create([
                    'transaksi_id' => $transaksi->id,
                    'material_id'  => $newItem['id'],
                    'jumlah'       => $qtyBaru,
                ]);
            }

            DB::commit();

            $suffixRoute = ($request->jenis == 1) ? 'keluars' : 'masuks';

            return response()->json([
                'success'  => true,
                'message'  => 'Data transaksi berhasil diperbarui!',
                'redirect' => route('material-' . $suffixRoute)
            ], 200);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal update: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $transaksi = TransaksiMaterial::findOrFail($id);

            $jenis = $transaksi->jenis;
            if ($transaksi->foto_bukti) {
                Storage::disk('public')->delete($transaksi->foto_bukti);
            }
            if ($transaksi->foto_sr_sebelum) {
                Storage::disk('public')->delete($transaksi->foto_sr_sebelum);
            }
            if ($transaksi->foto_sr_sesudah) {
                Storage::disk('public')->delete($transaksi->foto_sr_sesudah);
            }
            $transaksi->detail_transaksi()->delete();

            $transaksi->update(['delet_at' => '1']);

            DB::commit();

            $suffixRoute = ($jenis == 1) ? 'keluars' : 'masuks';

            return response()->json([
                'success'  => true,
                'message'  => 'Data transaksi berhasil dihapus!',
                'redirect' => route('material-' . $suffixRoute)
            ], 200);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus: ' . $e->getMessage()
            ], 500);
        }
    }

    private function generateKode($jenisTransaksi)
    {
        $prefix = ($jenisTransaksi == '1') ? 'TRK' : 'TRM';

        $last = TransaksiMaterial::where('jenis', $jenisTransaksi)
            ->orderBy('id', 'desc')
            ->first();

        if (!$last) {
            $nextNumber = 1;
        } else {
            $lastKode = (int) substr($last->kode_transaksi, 3);
            $nextNumber = $lastKode + 1;
        }

        $kodeBaru = $prefix . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

        return $kodeBaru;
    }
}
