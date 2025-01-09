<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Akun;
use App\Models\Pengguna;
use App\Models\Laporan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PenggunaControllerTest extends TestCase
{
    use RefreshDatabase;


    public function test_show_form()
    {

        $this->assertGuest();
        $response = $this->get('/register'); // Gunakan route() untuk lebih dinamis
        $response->assertStatus(200);
        $response->assertViewIs('pengguna.register');
    }

    // --- Feature Test: Proses Registrasi Pengguna ---
    public function test_store_pengguna()
    {

        if (!\DB::table('roles')->where('id_role', 6)->exists()) {
            \DB::table('roles')->insert([
                'id_role' => 6,
                'nama' => 'Role 6',
            ]);
        }

        $data = [
            'no_ktp' => '1234567890123456',
            'nama' => 'John Doe',
            'alamat' => 'Jl. Test 123',
            'no_hp' => '081234567890',
            'no_npwp' => '123456789012345',
            'no_rekening' => '1234567890123456',
            'tarif_daya' => '1300',
            'email' => 'johndoe@example.com',
            'password' => 'password123',
        ];

        $response = $this->post(route('pengguna.store'), $data);

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $response->assertSessionHas('success', 'Registrasi berhasil, Silahkan Login!');

        $this->assertDatabaseHas('penggunas', [
            'no_ktp' => $data['no_ktp'],
            'nama' => $data['nama'],
        ]);

        $this->assertDatabaseHas('akuns', [
            'username' => $data['email'],
            'id_role' => 6,
        ]);
    }

    // --- Feature Test: Menampilkan Laporan Pengguna Setelah Login ---
    public function test_laporan_pengguna()
{
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

      // Buat entri di tabel antrians terlebih dahulu
      $antrian = \App\Models\Antrian::create([
        'id_antrian' => 1,
        'tanggal' => now(),
        'nomor_antrian' => '002',
        'status' => 0,
        'id_pelanggan' => $pengguna->id_pelanggan,
        // Pastikan field lainnya disesuaikan jika perlu
    ]);


    // Buat laporan terkait pengguna
    $laporan = Laporan::create([
        'id_antrian' => 1,
        'tanggal_laporan' => now(),
        'is_hidden' => false,
            'layanan_via' => 'loket',
            'bidang_keluhan' => 'pemasaran',
            'detail_keluhan' => 'hujan',
            'surat' => '', //
            'id_antrian' => $antrian->id_antrian,
    ]);

    // Melakukan login
    $this->actingAs($akun);

    // Test akses laporan
    $response = $this->get('/antrian/laporan');
    $response->assertStatus(200);
    $response->assertViewIs('antrian.laporan');
    $response->assertSee($laporan->id_antrian);
}

}
