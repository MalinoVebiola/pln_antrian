<?php

namespace App\Http\Controllers;

use App\Models\Pengguna;
use App\Models\Laporan;
use App\Models\Antrian; // Pastikan model Antrian di-import
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    // Tampilkan halaman pelanggan
    public function index()
    {
        $laporans = Laporan::with('antrian.pengguna')->get();
    return view('admin.pelanggan', compact('laporans'));
    }

    // Ambil data pengguna berdasarkan nomor KTP
    public function getDataByKTP($noKtp)
    {
        $pengguna = Pengguna::where('no_ktp', $noKtp)->first();

        if ($pengguna) {
            return response()->json([
                'success' => true,
                'data' => [
                    'nama' => $pengguna->nama,
                    'alamat' => $pengguna->alamat,
                    'no_hp' => $pengguna->no_hp,
                    'no_npwp' => $pengguna->no_npwp,
                    'tarif_daya' => $pengguna->tarif_daya,
                ],
            ]);
        } else {
            return response()->json(['success' => false, 'message' => 'Data tidak ditemukan']);
        }
    }

    // Simpan data laporan
    public function store(Request $request)
    {
        // Validasi input
        $validatedData = $request->validate([
            'tanggal_laporan' => 'required|date',
            'no_ktp' => 'required|string|max:20',
            'layanan_via' => 'required|string|max:255',
            'bidang_keluhan' => 'required|string|max:255',
            'detail_keluhan' => 'required|string',
            'surat' => 'nullable|file|mimes:pdf,doc,docx|max:2048', // Pastikan file sesuai aturan
        ]);

        // Handle file upload jika ada
        if ($request->hasFile('surat')) {
            $validatedData['surat'] = $request->file('surat')->store('surat');
        }

        // Ambil no_ktp dari input yang divalidasi
$noKtp = $validatedData['no_ktp'];

// Cari pengguna berdasarkan no_ktp
$pengguna = Pengguna::where('no_ktp', $noKtp)->first();

if (!$pengguna) {
    return redirect()->back()->with('error', 'Nomor KTP tidak ditemukan di sistem!');
}

// Cari antrian yang sesuai dengan id_pelanggan pengguna
$antrian = Antrian::where('id_pelanggan', $pengguna->id_pelanggan)->first();

if ($antrian) {
    $validatedData['id_antrian'] = $antrian->id_antrian;
} else {
    // Jika tidak ada antrian, tambahkan logika alternatif
    return redirect()->back()->with('error', 'Pelanggan ini belum mengambil nomor antrian, ambil nomor antrian terlebih dahulu');
}


        // Simpan laporan ke database
        Laporan::create([
            'tanggal_laporan' => $validatedData['tanggal_laporan'],
            'layanan_via' => $validatedData['layanan_via'],
            'bidang_keluhan' => $validatedData['bidang_keluhan'],
            'detail_keluhan' => $validatedData['detail_keluhan'],
            'surat' => $validatedData['surat'] ?? null, // Jika surat tidak ada, biarkan null
            'id_antrian' => $validatedData['id_antrian'], // Menyimpan id_antrian yang diambil otomatis
            'status' =>'Menunggu', // Menyimpan id_antrian yang diambil otomatis
        ]);

        return redirect()->back()->with('success', 'Laporan berhasil disimpan!');
    }

    public function show($id)
{
    $laporans = Laporan::findOrFail($id);
    return view('admin.pelanggan', compact('laporans'));
}

public function update(Request $request, $id)
{
    $laporan = Laporan::findOrFail($id);
    $laporan->update($request->all());
    return response()->json($laporan);
}



public function destroy($id)
{
    $laporans = Laporan::findOrFail($id);
    $laporans->delete();
    return redirect()->route('laporan')->with('success', 'Laporan berhasil dihapus!');
}

//admin2 aksi

public function updateStatus(Request $request, $id)
{
    $laporan = Laporan::findOrFail($id);
    $laporan->status = $laporan->status === 'Menunggu' ? 'Diproses' : $laporan->status;
    $laporan->save();

    return response()->json(['success' => true, 'status' => $laporan->status]);
}

public function toggleVisibility($id)
{
    $laporan = Laporan::findOrFail($id);
    $laporan->is_hidden = !$laporan->is_hidden;
    $laporan->save();

    return response()->json(['success' => true, 'is_hidden' => $laporan->is_hidden]);
}



}
