<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function loginView()
    {
        if (Auth::check()) {
            return back();
        }

        return view('pages.auth.login');
    }

    public function login(Request $request)
    {
        if (Auth::check()) {
            return back();
        }
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
        $user = User::where('email', $credentials['email'])->first();
        if (!$user) {
            return back()
                ->with('error', 'Email or Password Incorrect!')
                ->withInput();
        }
        if ($user->is_active == 0 || $user->delet_at == 1) {
            return back()
                ->with('error', 'Akun anda non aktif, silakan hubungi admin.')
                ->withInput();
        }
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        }
        return back()
            ->with('error', 'Email or Password Incorrect!')
            ->withInput();
    }


    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
