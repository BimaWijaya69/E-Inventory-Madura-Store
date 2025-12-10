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
        return view('pages.materials.index',  ['data' => $data, 'breadcrumb' => $breadcrumb]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        $lastMaterial = Material::orderBy('id', 'desc')->first();
        $nextCode = 'MAT-001';

        if ($lastMaterial) {
            if (preg_match('/MAT-(\d+)/', $lastMaterial->kode_material, $matches)) {
                $nextNumber = intval($matches[1]) + 1;
                $nextCode = 'MAT-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
            } else {
                $nextNumber = $lastMaterial->id + 1;
                $nextCode = 'MAT-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
            }
        }
        // sesuaikan nanti
        return view('admin.master.create', compact('nextCode'));
    }

    /**
     * Store a newly created resource in storage.
     */
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
                'success' => true
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = Material::where('id', $id)->firstOrFail();
        if ($data->nama_material != $request->nama_material) {
            $request->validate([
                'nama_material' => 'required|unique:materials,nama_material',
            ], [
                'nama_material.unique' => 'Nama material sudah ada.',
            ]);
        }

        $update = $data->update($request->all());
        if ($update) {
            return response()->json([
                'success' => true
            ]);
        } else {
            return response()->json([
                'success' => false
            ]);
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
}
