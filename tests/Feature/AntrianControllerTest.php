<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Antrian;
use App\Models\Pengguna;
use App\Models\Akun;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

class AntrianControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test halaman index antrian.
     *
     * @return void
     */
    public function test_index()
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

        // Membuat antrian untuk pengguna tersebut
        Antrian::create([
            'id_pelanggan' => $pengguna->id_pelanggan,
            'tanggal' => now()->toDateString(),
            'nomor_antrian' => '001',
            'status' => 0,
        ]);

        $response = $this->get('/antrian'); // Sesuaikan dengan route yang digunakan untuk index

        $response->assertStatus(200);
        $response->assertViewIs('antrian.index');
        $response->assertSee('Antrian User'); // Memastikan halaman memiliki judul
    }

    /**
     * Test mengambil nomor antrian.
     *
     * @return void
     */
    public function test_ambil_nomor_antrian()
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
            'id_pelanggan' => 6
        ]);
        $this->actingAs($akun);

        // Melakukan request untuk ambil nomor antrian
        $response = $this->post('/antrian/ambil', [
            'id_pelanggan' => $pengguna->id_pelanggan,
        ]);

        $response->assertStatus(200);
        $response->assertJson(['message' => 'Sukses']);
    }

    /**
     * Test cetak nomor antrian.
     *
     * @return void
     */
    public function test_cetak_nomor_antrian()
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

        $antrian = Antrian::create([
            'id_pelanggan' => $pengguna->id_pelanggan,
            'tanggal' => now()->toDateString(),
            'nomor_antrian' => '001',
            'status' => 0,
        ]);

        // Melakukan request untuk cetak nomor antrian
        $response = $this->get("/antrian/cetak/{$antrian->id_antrian}");

        $response->assertStatus(200);
        $response->assertViewIs('antrian.cetak');
        $response->assertSee($antrian->nomor_antrian); // Memastikan nomor antrian ada di halaman
    }



}
