<?php

namespace App\Http\Controllers;

use App\Models\DetailTransaksiMaterial;
use App\Models\TransaksiMaterial;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TransaksiMaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
                    DetailTransaksiMaterial::create([
                        'transaksi_id' => $transaksi->id,
                        'material_id'  => $item['id'],
                        'jumlah'       => $item['jumlah'],
                    ]);
                }
            }

            DB::commit();
            $suffixRoute = ($request->jenis == 1) ? 'pengeluaran' : 'penerimaan';

            return response()->json([
                'success'  => true,
                'message'  => 'Data transaksi berhasil disimpan!',
                'redirect' => route('transaksi-material-' . $suffixRoute)
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
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $transaksi = TransaksiMaterial::findOrFail($id);
        DB::beginTransaction();

        try {
            $data = [
                'tanggal'              => $request->tanggal,
                'jenis'                => $request->jenis,
                'nama_pihak_transaksi' => $request->nama_pihak_transaksi,
                'keperluan'            => $request->keperluan,
                'nomor_pelanggan'      => $request->nomor_pelanggan,
            ];

            if ($request->hasFile('foto_bukti')) {
                if ($transaksi->foto_bukti) {
                    Storage::disk('public')->delete($transaksi->foto_bukti);
                }
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
            $transaksi->detail_transaksi()->delete();

            if ($request->has('materials')) {
                foreach ($request->materials as $item) {
                    DetailTransaksiMaterial::create([
                        'transaksi_id' => $transaksi->id,
                        'material_id'  => $item['id'],
                        'jumlah'       => $item['jumlah'],
                    ]);
                }
            }

            DB::commit();

            $suffixRoute = ($request->jenis == 1) ? 'pengeluaran' : 'penerimaan';

            return response()->json([
                'success'  => true,
                'message'  => 'Data transaksi berhasil diperbarui!',
                'redirect' => route('transaksi-material-' . $suffixRoute)
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

            $transaksi->delete();

            DB::commit();

            $suffixRoute = ($jenis == 1) ? 'pengeluaran' : 'penerimaan';

            return response()->json([
                'success'  => true,
                'message'  => 'Data transaksi berhasil dihapus!',
                'redirect' => route('transaksi-material-' . $suffixRoute)
            ], 200);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus: ' . $e->getMessage()
            ], 500);
        }
    }
}
