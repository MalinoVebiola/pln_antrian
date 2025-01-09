<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Antrian;
use App\Models\Pengguna;

class AntrianController extends Controller
{
    public function index()
    {
        $judul = "Antrian User";
        $logoinstansi = "logo-pln.png";
        $namainstansi = "PLN UP3 Bintaro";

        // Mendapatkan ID akun dan ID pelanggan
        $idAkun = Auth::id();
        $idPelanggan = Pengguna::where('id_akun', $idAkun)->value('id_pelanggan');

        // Ambil nomor antrian terakhir yang diambil oleh pengguna hari ini
        $nomorAntrian = Antrian::where('id_pelanggan', $idPelanggan)
            ->whereDate('tanggal', now()->toDateString()) // Pastikan tanggal sama
            ->orderBy('id_antrian', 'desc') // Urutkan berdasarkan ID antrian terbaru
            ->first(); // Ambil yang terbaru (terakhir kali diambil)

        // Ambil antrian yang sedang berjalan
        $antrianSekarang = Antrian::whereDate('tanggal', now()->toDateString())
            ->where('status', 1) // Status 1 berarti antrian sedang berjalan
            ->orderBy('id_antrian', 'desc')
            ->value('nomor_antrian') ?? 'Belum Ada';

        // Kirim data nomor antrian yang ditemukan
        return view('antrian.index', compact('judul', 'logoinstansi', 'namainstansi', 'nomorAntrian', 'antrianSekarang', 'idPelanggan'));
    }

    public function ambilNomor(Request $request)
    {
        // Mendapatkan ID akun dan ID pelanggan
        $idAkun = Auth::id();
        $idPelanggan = Pengguna::where('id_akun', $idAkun)->value('id_pelanggan');

        if (!$idPelanggan) {
            return response()->json(['message' => 'Pelanggan tidak ditemukan.'], 404);
        }

        // Ambil nomor antrian terakhir hari ini untuk pengguna ini
        $nomorAntrianTerakhir = Antrian::where('id_pelanggan', $idPelanggan)
                                       ->whereDate('tanggal', now())
                                       ->max('nomor_antrian');

        // Tentukan nomor antrian baru
        $nomorAntrianBaru = str_pad($nomorAntrianTerakhir + 1, 3, '0', STR_PAD_LEFT);

        // Buat entri antrian baru
        $antrian = Antrian::create([
            'id_pelanggan' => $idPelanggan,
            'tanggal' => now()->toDateString(),
            'nomor_antrian' => $nomorAntrianBaru,
            'status' => 0, // Status Menunggu
        ]);

        // Simpan nomor antrian di session
        session(['nomor_antrian' => $antrian->nomor_antrian]);

        return response()->json([
            'message' => 'Sukses',
            'nomor_antrian' => $antrian->nomor_antrian,
            'nomor_antrian_terakhir' => $nomorAntrianTerakhir // Menampilkan nomor antrian terakhir
        ]);
    }

    public function cetak($id)
    {
        $antrian = Antrian::where('id_antrian', $id)
            ->where('tanggal', now()->toDateString())
            ->first();

        if (!$antrian) {
            abort(404, 'Nomor antrian tidak ditemukan.');
        }

        return view('antrian.cetak', compact('antrian'));
    }

    public function show()
    {
        $data['antrian'] = Antrian::all(); // Mengambil semua data
        return view('admin.antrian', $data);
    }

    public function create()
    {
        $data = $this->request->getPost();
        $this->antrianModel->addAntrian([
            'tanggal' => $data['tanggal'],
            'no_antrian' => $data['no_antrian'],
            'status' => 0 // Status Menunggu
        ]);

        return redirect()->to('/antrian');
    }

    public function getAntrianData()
    {
        $tanggal = now()->toDateString(); // Mendapatkan tanggal hari ini

        // Ambil data antrian hanya untuk tanggal hari ini dan masukkan relasi pengguna
        $antrians = Antrian::where('tanggal', $tanggal)
            ->with('pengguna') // Memuat relasi pengguna
            ->orderBy('id_antrian', 'desc')
            ->get()
            ->map(function($item) {
                return [
                    'id_antrian' => $item->id_antrian,
                    'nomor_antrian' => $item->nomor_antrian,
                    'tanggal' => $item->tanggal,
                    'status' => $item->status == 1 ? 'Dipanggil' : 'Menunggu', // Mengubah status menjadi string
                    'pengguna' => [
                        'nama' => $item->pengguna->nama,
                        'no_ktp' => $item->pengguna->no_ktp,
                    ],
                ];
            });

        // Mendapatkan data lainnya
        $antrianSekarang = Antrian::where('tanggal', $tanggal)
            ->where('status', 1) // Status 1 berarti antrian sedang berjalan
            ->orderBy('updated_at', 'DESC')
            ->first();

        $antrianSelanjutnya = Antrian::where('tanggal', $tanggal)
            ->where('status', 0) // Status 0 berarti antrian masih menunggu
            ->orderBy('nomor_antrian', 'ASC')
            ->first();

        return response()->json([
            'data' => $antrians,  // Mengirim data yang sudah difilter dan termasuk pengguna
            'antrian_sekarang' => $antrianSekarang ? $antrianSekarang->nomor_antrian : '-',
            'antrian_selanjutnya' => $antrianSelanjutnya ? $antrianSelanjutnya->nomor_antrian : '-',
        ]);
    }

    public function update(Request $request, $id)
    {
        $antrian = Antrian::find($id);

        if (!$antrian) {
            return response()->json(['success' => false, 'message' => 'Antrian tidak ditemukan'], 404);
        }

        // Ubah status antrian menjadi 'Diproses' (1)
        $antrian->status = 1;  // Status 'Diproses' = 1
        $antrian->save();

        // Mendapatkan nomor antrian sekarang (status 1: Diproses)
        $antrianSekarang = Antrian::where('tanggal', now()->toDateString())
                                  ->where('status', 1) // Status 1 = Diproses
                                  ->orderBy('updated_at', 'desc')
                                  ->first();

        // Mendapatkan nomor antrian selanjutnya (status 0: Menunggu)
        $antrianSelanjutnya = Antrian::where('tanggal', now()->toDateString())
                                     ->where('status', 0) // Status 0 = Menunggu
                                     ->orderBy('nomor_antrian', 'asc')
                                     ->first();

        return response()->json([
            'success' => true,
            'antrian_sekarang' => $antrianSekarang ? $antrianSekarang->nomor_antrian : '-',
            'antrian_selanjutnya' => $antrianSelanjutnya ? $antrianSelanjutnya->nomor_antrian : '-',
        ]);
    }

}
