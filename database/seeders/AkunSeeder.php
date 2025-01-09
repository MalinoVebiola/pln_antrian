<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AkunSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Menambahkan beberapa akun dengan id_role yang merujuk ke tabel departemen
        DB::table('akuns')->insert([
            [
                'username' => 'admin@email.com',
                'password' => Hash::make('12345'), // password yang sudah di-hash
                'id_role' => 1, // id_role 1 -- departemen pemasaran
                'id_departemen' => 1,
            ],
            [
                'username' => 'admin2@email.com',
                'password' => Hash::make('12345'),
                'id_role' => 2, // id_role 2 -- departemen 'niaga'
                'id_departemen' => 2,
            ],
            [
                'username' => 'admin3@email.com',
                'password' => Hash::make('12345'),
                'id_role' => 3, // id_role 3 -- departemen 'konstruksi'
                'id_departemen' => 3,
            ],
            [
                'username' => 'admin4@email.com',
                'password' => Hash::make('12345'),
                'id_role' => 4, // id_role 4 -- departemen 'jairngan'
                'id_departemen' => 4,
            ],
            [
                'username' => 'admin5@email.com',
                'password' => Hash::make('12345'),
                'id_role' => 5, // id_role 5 -- departemen 'Te'
                'id_departemen' => 5,
            ],
            [
                'username' => 'vebiola@gmail.com',
                'password' => Hash::make('12345'),
                'id_role' => 6, // id_role 6 --pelanggan
                'id_departemen' => NULL,
            ],
            // Tambahkan data lainnya sesuai kebutuhan
        ]);
    }
}
