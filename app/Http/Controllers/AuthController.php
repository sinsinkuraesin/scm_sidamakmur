<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    // Tampilkan halaman login
    public function showLoginForm()
    {
        return view('login'); // Sesuaikan dengan nama file view login kamu
    }

    // Proses login
    public function login(Request $request)
    {
        // Validasi form login
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Coba login
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();


            // Arahkan berdasarkan role
            if (Auth::user()->role === 'admin') {
                return redirect('/beranda-admin')->with('success', 'Selamat datang, ' . Auth::user()->name . '!');
            } elseif (Auth::user()->role === 'pemilik') {
                return redirect('/beranda-pemilik')->with('success', 'Selamat datang, ' . Auth::user()->name . '!');
            } else {
                Auth::logout(); // Role tidak valid
                return redirect('/login')->withErrors([
                    'email' => 'Role tidak dikenali.',
                ]);
            }
        }

        // Jika gagal login
        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    // Proses logout
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
