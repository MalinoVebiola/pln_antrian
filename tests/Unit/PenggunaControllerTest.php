<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Akun;
use App\Models\Pengguna;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PenggunaControllerTest extends TestCase
{
    use RefreshDatabase;


    // --- Unit Test: Memastikan Password di-hash dengan Benar ---
    public function test_password_hashing()
    {
        // Pastikan ada role dengan id_role = 6
        if (!\DB::table('roles')->where('id_role', 6)->exists()) {
            \DB::table('roles')->insert([
                'id_role' => 6,
                'nama' => 'Role 6', // Sesuaikan nama role dengan yang diinginkan
            ]);
        }

        // Membuat akun dengan id_role = 6
        $password = 'password123';
        $akun = Akun::create([
            'username' => 'hasheduser@example.com',
            'password' => Hash::make($password),
            'id_role' => 6,  // Pastikan id_role sesuai dengan role yang ada
        ]);

        // Memastikan password di-hash dengan benar
        $this->assertTrue(Hash::check($password, $akun->password));
    }
}
