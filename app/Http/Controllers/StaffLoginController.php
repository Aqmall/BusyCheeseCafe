<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StaffLoginController extends Controller
{
    // Menampilkan halaman form login
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Memproses login
    public function login(Request $request)
    {
        // 1. Validasi input dari form
        $credentials = $request->validate([
            'email' => 'required|email', // Diubah dari username ke email untuk standar Laravel
            'password' => 'required|string',
        ]);

        // 2. Coba untuk mengotentikasi pengguna
        if (Auth::attempt($credentials)) {
            // Jika berhasil, buat session baru untuk keamanan
            $request->session()->regenerate();
            
            // Simpan peran pengguna ke session (jika ada kolom 'role' di tabel users)
            // Untuk sekarang, kita asumsikan semua yang bisa login adalah staf
            $user = Auth::user();
            // Anda bisa menambahkan kolom 'role' di tabel users nanti untuk membedakan kasir/manajer
            // Contoh: $request->session()->put('user_role', $user->role); 
            $request->session()->put('user_role', $user->name); // Menggunakan nama sebagai peran sementara

            // Arahkan ke dasbor admin
            return redirect()->intended(route('admin.dashboard'));
        }

        // 3. Jika otentikasi gagal
        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    // Memproses logout
    public function logout(Request $request)
    {
        Auth::logout(); // Menggunakan fungsi logout bawaan Laravel

        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/');
    }
}