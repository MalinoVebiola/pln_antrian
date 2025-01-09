<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\Akun;

class AkunController extends Controller
{
    // Menampilkan form login
    public function showForm()
    {
        return view('login');
    }

    // Proses login
    public function login(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'u' => 'required',  // Username
            'p' => 'required',  // Password
        ]);

        // Cek apakah username ditemukan di database
        $akun = Akun::where('username', $validated['u'])->first();

        // Cek apakah akun ditemukan dan password valid
        if ($akun && Hash::check($validated['p'], $akun->password)) {
            // Jika password valid, login pengguna
            Auth::login($akun);

            // Redirect ke halaman sesuai dengan role pengguna
            switch ($akun->id_role) {
                case 1:
                    return redirect()->route('admin.index')->with('success', 'Login berhasil!');
                case 2:
                case 3:
                case 4:
                case 5:
                    return redirect()->route('admin2.index')->with('success', 'Login berhasil!');
                case 6:
                    return redirect()->route('home')->with('success', 'Login berhasil!');
                default:
                    Auth::logout();  // Jika role tidak dikenali, logout pengguna
                    return redirect()->route('login')->withErrors(['loginError' => 'Role tidak dikenali.']);
            }
        } else {
            // Jika akun tidak ditemukan atau password salah
            return back()->withErrors(['loginError' => 'Username atau password salah.']);
        }
    }

    // Logout
    public function logout()
    {
        Auth::logout();
        return redirect()->route('login')->with('success', 'Logout berhasil');
    }
}
