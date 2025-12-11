<?php

namespace App\Http\Controllers;

use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Auth::user();
        $breadcrumb = (object) [
            'list' => ['Manejemen Material', '']
        ];

        $materials = Material::all();
        return view('pages.materials.index',  ['data' => $data, 'breadcrumb' => $breadcrumb, 'materials' => $materials]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_material' => 'required|unique:materials,nama_material',
        ], [
            'nama_material.unique' => 'Nama material sudah ada.',
        ]);

        $data = $request->all();
        $data['stok'] = $request->stok_awal;

        $saveData = Material::create($data);

        if ($saveData) {
            return response()->json([
                'success' => true,
            ]);
        } else {
            return response()->json([
                'success' => false
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id) {}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return Material::findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = Material::findOrFail($id);

        if ($data->nama_material !== $request->e_nama_material) {
            $request->validate([
                'e_nama_material' => 'required|unique:materials,nama_material,' . $id,
            ], [
                'e_nama_material.unique' => 'Nama material sudah ada.',
            ]);
        }

        try {

            $updateData = [
                'nama_material' => $request->e_nama_material,
                'satuan'        => $request->e_satuan,
                'min_stok'      => $request->e_min_stok,
                'stok_awal'     => $request->e_stok_awal,
            ];

            $data->update($updateData);

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil diperbarui'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui data: ' . $e->getMessage()
            ], 500);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Material::where('id', $id)->update(['delet_at' => '1']);
        return response()->json([
            'success' => true
        ]);
    }

    public function generateKode()
    {
        $last = Material::orderBy('id', 'desc')->first();

        if (!$last) {
            $nextNumber = 1;
        } else {
            $lastKode = (int) substr($last->kode_material, 2);
            $nextNumber = $lastKode + 1;
        }

        $kode = 'MT' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

        return response()->json([
            'kode' => $kode
        ]);
    }
}
