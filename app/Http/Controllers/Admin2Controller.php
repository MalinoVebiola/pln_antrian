<?php


namespace App\Http\Controllers;
use App\Models\Laporan;
use App\Models\Pengguna;
use App\Models\Role;

use Illuminate\Http\Request;


class Admin2Controller extends Controller
{
    /**
     * Tampilkan halaman admin index.
     */
    public function index()
    {
        return view('admin2.index');
    }

    public function laporan(Request $request)
{
    // Ambil role user yang sedang login
    $role = auth()->user()->role->nama; // Pastikan relasi ke role sudah diatur di model User

    // Ambil laporan berdasarkan bidang_keluhan yang sesuai dengan role user login
    $laporans = Laporan::select('laporans.*')
        ->join('antrians', 'laporans.id_antrian', '=', 'antrians.id_antrian')  // Join dengan antrians
        ->join('penggunas', 'antrians.id_pelanggan', '=', 'penggunas.id_pelanggan')  // Join dengan pengguna
        ->join('akuns', 'penggunas.id_akun', '=', 'akuns.id_akun')  // Join dengan akuns
        ->join('roles', 'akuns.id_role', '=', 'roles.id_role')  // Join dengan roles
        ->where('laporans.bidang_keluhan', $role)  // Cocokkan bidang_keluhan dengan nama role
        ->get();

    // Kirim data laporan ke view
    return view('admin2.laporan', compact('laporans'));
}













}
