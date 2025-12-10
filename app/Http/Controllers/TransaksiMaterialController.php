<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransaksiMaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function materialMasukView()
    {
        $data = Auth::user();
        $breadcrumb = (object) [
            'list' => ['Materaial Masuk', '']
        ];
        return view('pages.transaksi.material-masuk',  ['data' => $data, 'breadcrumb' => $breadcrumb]);
    }

    public function materialKeluarView()
    {
        $data = Auth::user();
        $breadcrumb = (object) [
            'list' => ['Material Keluar', '']
        ];
        return view('pages.transaksi.material-keluar',  ['data' => $data, 'breadcrumb' => $breadcrumb]);
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
        //
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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
