<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Pengguna;
use App\Models\Akun;
use App\Models\Laporan;
use App\Models\Antrian;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LaporanControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test halaman laporan.
     *
     * @return void
     */
    public function test_index()
    {
        // Membuat laporan dengan antrian terkait
        $this->withoutMiddleware();
         // Pastikan role dengan id_role 6 ada sebelum melanjutkan
     if (!\DB::table('roles')->where('id_role', 6)->exists()) {
        \DB::table('roles')->insert([
            'id_role' => 6,
            'nama' => 'Role 6',
        ]);
    }

    // Buat akun dan pengguna untuk pengujian
    $akun = Akun::create([
        'username' => 'johndoe@example.com',
        'password' => Hash::make('password123'),
        'id_role' => 6, // Role harus ada
    ]);

    $pengguna = Pengguna::create([
        'no_ktp' => '1234567890123456',
        'nama' => 'John Doe',
        'alamat' => 'Jl. Test 123',
        'no_hp' => '081234567890',
        'no_npwp' => '123456789012345',
        'no_rekening' => '1234567890123456',
        'tarif_daya' => '1300',
        'id_akun' => $akun->id_akun,
    ]);

        // Membuat antrian untuk pengguna tersebut
        $antrian=Antrian::create([
            'id_pelanggan' => $pengguna->id_pelanggan,
            'tanggal' => now()->toDateString(),
            'nomor_antrian' => '001',
            'status' => 0,
        ]);
        $laporan = Laporan::create([
            'tanggal_laporan' => now(),
            'layanan_via' => 'Online',
            'bidang_keluhan' => 'Tagihan',
            'detail_keluhan' => 'Pembayaran tidak tercatat',
            'status' => 'Menunggu',
            'id_antrian' => $antrian->id_antrian,
        ]);

        // Mengakses halaman laporan
        $response = $this->get(route('laporan.index')); // Sesuaikan dengan route yang digunakan

        // Memastikan laporan ditampilkan
        $response->assertStatus(200);
        $response->assertViewIs('admin.pelanggan');

    }

    /**
     * Test menyimpan laporan.
     *
     * @return void
     */
    public function test_store_laporan()
    {
        $this->withoutMiddleware();
        if (!\DB::table('roles')->where('id_role', 6)->exists()) {
            \DB::table('roles')->insert([
                'id_role' => 6,
                'nama' => 'Role 6',
            ]);
        }

        // Buat akun dan pengguna untuk pengujian
        $akun = Akun::create([
            'username' => 'johndoe@example.com',
            'password' => Hash::make('password123'),
            'id_role' => 6, // Role harus ada
        ]);

        $pengguna = Pengguna::create([
            'no_ktp' => '1234567890123456',
            'nama' => 'John Doe',
            'alamat' => 'Jl. Test 123',
            'no_hp' => '081234567890',
            'no_npwp' => '123456789012345',
            'no_rekening' => '1234567890123456',
            'tarif_daya' => '1300',
            'id_akun' => $akun->id_akun,
        ]);

            // Membuat antrian untuk pengguna tersebut
            Antrian::create([
                'id_pelanggan' => $pengguna->id_pelanggan,
                'tanggal' => now()->toDateString(),
                'nomor_antrian' => '001',
                'status' => 0,
            ]);
        // Data laporan yang akan disimpan
        $data = [
            'tanggal_laporan' => now(),
            'no_ktp' => '1234567890123456',
            'layanan_via' => 'Online',
            'bidang_keluhan' => 'Tagihan',
            'detail_keluhan' => 'Pembayaran tidak tercatat',
            'surat' => null, // Tidak ada file surat
        ];

        // Melakukan request untuk menyimpan laporan
        $response = $this->post(route('laporan.store'), $data); // Sesuaikan dengan route yang digunakan

        // Memastikan laporan berhasil disimpan
        $response->assertRedirect();

        // Memastikan data laporan ada di database
        $this->assertDatabaseHas('laporans', [
            'layanan_via' => $data['layanan_via'],
            'bidang_keluhan' => $data['bidang_keluhan'],
        ]);
    }

    /**
     * Test mengupdate status laporan.
     *
     * @return void
     */
    public function test_update_status()
    {
        $this->withoutMiddleware();
        if (!\DB::table('roles')->where('id_role', 6)->exists()) {
            \DB::table('roles')->insert([
                'id_role' => 6,
                'nama' => 'Role 6',
            ]);
        }

        // Buat akun dan pengguna untuk pengujian
        $akun = Akun::create([
            'username' => 'johndoe@example.com',
            'password' => Hash::make('password123'),
            'id_role' => 6, // Role harus ada
        ]);

        $pengguna = Pengguna::create([
            'no_ktp' => '1234567890123456',
            'nama' => 'John Doe',
            'alamat' => 'Jl. Test 123',
            'no_hp' => '081234567890',
            'no_npwp' => '123456789012345',
            'no_rekening' => '1234567890123456',
            'tarif_daya' => '1300',
            'id_akun' => $akun->id_akun,
        ]);

            // Membuat antrian untuk pengguna tersebut
            $antrian=Antrian::create([
                'id_pelanggan' => $pengguna->id_pelanggan,
                'tanggal' => now()->toDateString(),
                'nomor_antrian' => '001',
                'status' => 0,

            ]);
        // Membuat laporan
        $laporan = Laporan::create([
            'tanggal_laporan' => now(),
            'layanan_via' => 'Online',
            'bidang_keluhan' => 'Tagihan',
            'detail_keluhan' => 'Pembayaran tidak tercatat',
            'status' => 'Menunggu',
            'id_antrian' => $antrian->id_antrian,
        ]);

        // Melakukan request untuk update status
        $response = $this->post(route('laporan.updateStatus', $laporan->id)); // Sesuaikan dengan route yang digunakan

        // Memastikan status laporan diubah menjadi 'Diproses'
        $response->assertJson(['success' => true, 'status' => 'Diproses']);

        // Memastikan status di database telah diperbarui
        $laporan->refresh();
        $this->assertEquals('Diproses', $laporan->status);
    }

    /**
     * Test toggle visibility laporan.
     *
     * @return void
     */
    public function test_toggle_visibility()
    {
        $this->withoutMiddleware();
        // Membuat laporan
        if (!\DB::table('roles')->where('id_role', 6)->exists()) {
            \DB::table('roles')->insert([
                'id_role' => 6,
                'nama' => 'Role 6',
            ]);
        }

        // Buat akun dan pengguna untuk pengujian
        $akun = Akun::create([
            'username' => 'johndoe@example.com',
            'password' => Hash::make('password123'),
            'id_role' => 6, // Role harus ada
        ]);

        $pengguna = Pengguna::create([
            'no_ktp' => '1234567890123456',
            'nama' => 'John Doe',
            'alamat' => 'Jl. Test 123',
            'no_hp' => '081234567890',
            'no_npwp' => '123456789012345',
            'no_rekening' => '1234567890123456',
            'tarif_daya' => '1300',
            'id_akun' => $akun->id_akun,
        ]);

            // Membuat antrian untuk pengguna tersebut
            $antrian=Antrian::create([
                'id_pelanggan' => $pengguna->id_pelanggan,
                'tanggal' => now()->toDateString(),
                'nomor_antrian' => '001',
                'status' => 0,

            ]);
        // Membuat laporan
        $laporan = Laporan::create([
            'tanggal_laporan' => now(),
            'layanan_via' => 'Online',
            'bidang_keluhan' => 'Tagihan',
            'detail_keluhan' => 'Pembayaran tidak tercatat',
            'status' => 'Menunggu',
            'id_antrian' => $antrian->id_antrian,
            'is_hidden' => 0,  // Gunakan 0 untuk false
        ]);

            // Melakukan request untuk toggle visibility
            $response = $this->post(route('laporan.toggleVisibility', $laporan->id));

            // Memastikan response mengembalikan success dan status visibility baru
            $response->assertJson(['success' => true, 'is_hidden' => 1]);  // 1 untuk true

            // Memastikan status visibility di database telah diperbarui
            $laporan->refresh();  // Untuk memastikan data terbaru setelah update
            $this->assertTrue($laporan->is_hidden == 1);  // Memastikan 'is_hidden' bernilai 1 (true)
        }



    /**
     * Test menghapus laporan.
     *
     * @return void
     */
    public function test_destroy_laporan()
    {
        $this->withoutMiddleware();
        if (!\DB::table('roles')->where('id_role', 6)->exists()) {
            \DB::table('roles')->insert([
                'id_role' => 6,
                'nama' => 'Role 6',
            ]);
        }

        // Buat akun dan pengguna untuk pengujian
        $akun = Akun::create([
            'username' => 'johndoe@example.com',
            'password' => Hash::make('password123'),
            'id_role' => 6, // Role harus ada
        ]);

        $pengguna = Pengguna::create([
            'no_ktp' => '1234567890123456',
            'nama' => 'John Doe',
            'alamat' => 'Jl. Test 123',
            'no_hp' => '081234567890',
            'no_npwp' => '123456789012345',
            'no_rekening' => '1234567890123456',
            'tarif_daya' => '1300',
            'id_akun' => $akun->id_akun,
        ]);

            // Membuat antrian untuk pengguna tersebut
            $antrian=Antrian::create([
                'id_pelanggan' => $pengguna->id_pelanggan,
                'tanggal' => now()->toDateString(),
                'nomor_antrian' => '001',
                'status' => 0,

            ]);
        // Membuat laporan
        $laporan = Laporan::create([
            'tanggal_laporan' => now(),
            'layanan_via' => 'Online',
            'bidang_keluhan' => 'Tagihan',
            'detail_keluhan' => 'Pembayaran tidak tercatat',
            'status' => 'Menunggu',
            'id_antrian' => $antrian->id_antrian,
        ]);

        // Melakukan request untuk menghapus laporan
        $response = $this->delete(route('laporan.destroy', $laporan->id)); // Sesuaikan dengan route yang digunakan

        // Memastikan laporan dihapus
        $response = $this->get(route('laporan.index'));
        $response->assertSessionHas('success', 'Laporan berhasil dihapus!');

        // Memastikan laporan sudah dihapus dari database
        $this->assertDatabaseMissing('laporans', [
            'id' => $laporan->id,
        ]);
    }
}
