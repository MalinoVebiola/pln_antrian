<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengguna;
use App\Models\Akun;
use App\Models\Laporan;
use Illuminate\Support\Facades\Hash;

class PenggunaController extends Controller
{
    // Tampilkan form registrasi
    public function showForm()
    {
        return view('pengguna.register');
    }

    public function laporan()
    {
        // Ambil ID akun dari pengguna yang sedang login
        $idAkun = auth()->id();

        // Ambil laporan berdasarkan ID akun login melalui relasi
        $laporans = Laporan::whereHas('antrian.pengguna', function ($query) use ($idAkun) {
            $query->where('id_akun', $idAkun);
        })->where('is_hidden', false)
          ->get();

        // Return ke view dengan data laporan
        return view('antrian.laporan', compact('laporans'));
    }





    // Simpan data ke database
    public function store(Request $request)
    {
        // Validasi data
        $validated = $request->validate([
            'no_ktp' => 'required|unique:penggunas,no_ktp|max:16',
            'nama' => 'required',
            'alamat' => 'required',
            'no_hp' => 'required|unique:penggunas,no_hp',
            'no_npwp' => 'required',
            'no_rekening' => 'required',
            'tarif_daya' => 'required',
            'email' => 'required|email|unique:akuns,username',
            'password' => 'required|min:6',
        ]);

        // Buat akun terlebih dahulu
        $akun = Akun::create([
            'username' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'id_role' => 6,
            
        ]);

        // Simpan data pengguna
        Pengguna::create([
            'no_ktp' => $validated['no_ktp'],
            'nama' => $validated['nama'],
            'alamat' => $validated['alamat'],
            'no_hp' => $validated['no_hp'],
            'no_npwp' => $validated['no_npwp'],
            'no_rekening' => $validated['no_rekening'],
            'tarif_daya' => $validated['tarif_daya'],
            'id_akun' => $akun->id_akun,
        ]);

        return redirect()->route('login')->with('success', 'Registrasi berhasil, Silahkan Login!');

    }
}
