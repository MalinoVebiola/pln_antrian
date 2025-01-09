<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Akun;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AkunControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test show form login.
     *
     * @return void
     */
    public function test_show_form()
    {
        $response = $this->get('/login'); // Menyesuaikan dengan route login kamu

        $response->assertStatus(200); // Memastikan halaman login ditampilkan
        $response->assertViewIs('login'); // Memastikan view yang digunakan adalah 'login'
    }

    /**
     * Test login with invalid credentials.
     *
     * @return void
     */
    public function test_login_with_invalid_credentials()
    {
        // Membuat akun dummy untuk login
        $akun = Akun::factory()->create([
            'username' => 'testuser',
            'password' => Hash::make('password123'),
        ]);

        // Melakukan request login dengan username atau password yang salah
        $response = $this->post('/login', [
            'u' => 'testuser',
            'p' => 'wrongpassword',
        ]);

        // Memastikan login gagal dan kembali ke halaman login dengan error
        $response->assertStatus(302); // Mengharapkan redirect
        $response->assertSessionHasErrors('loginError'); // Memastikan error login
    }

    /**
     * Test logout.
     *
     * @return void
     */
    public function test_logout()
    {
        // Membuat akun dummy dan login terlebih dahulu
        $akun = Akun::factory()->create([
            'username' => 'testuser',
            'password' => Hash::make('password123'),
        ]);

        $this->actingAs($akun);

        // Melakukan request logout
        $response = $this->post('/logout'); // Sesuaikan dengan route logout kamu

        // Memastikan logout berhasil dan diarahkan ke halaman login
        $response->assertRedirect(route('login')); // Redirect ke halaman login
        $this->assertGuest(); // Memastikan pengguna sudah logout
    }
}
