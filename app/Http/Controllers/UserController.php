<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Auth::user();
        $users = User::all();
        $breadcrumb = (object) [
            'list' => ['Manejemen Pengguna', '']
        ];
        return view('pages.man-users.index',  ['data' => $data, 'users' => $users, 'breadcrumb' => $breadcrumb]);
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
        $request->validate([
            'email' => 'required|string|email|max:255|unique:users',
        ], [
            'email.unique' => 'Email sudah terdaftar.',
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->role = $request->role;
        $user->save();

        return response()->json(['success' => true]);
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
        $user = User::find($id);
        return response()->json($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User tidak ditemukan'], 404);
        }
        $user->name = $request->e_name;
        $user->email = $request->e_email;
        $user->role = $request->e_role;
        if ($request->filled('new_password')) {

            if (!$request->filled('old_password')) {
                return response()->json([
                    'message' => 'Silakan masukkan password lama untuk konfirmasi perubahan.'
                ], 422);
            }

            if (!Hash::check($request->old_password, $user->password)) {
                return response()->json([
                    'message' => 'Password lama yang Anda masukkan salah.'
                ], 422);
            }

            $user->password = Hash::make($request->new_password);
        }
        $user->save();
        return response()->json(['success' => true, 'message' => 'Data berhasil diperbarui']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        User::where('id', $id)->update(['delet_at' => '1']);
        return response()->json(['success' => true]);
    }

    public function is_active($id)
    {
        $user = User::findOrFail($id);
        $user->is_active = $user->is_active ? 0 : 1;
        $user->save();
        return response()->json([
            'success' => true,
            'is_active' => $user->is_active
        ]);
    }
}
