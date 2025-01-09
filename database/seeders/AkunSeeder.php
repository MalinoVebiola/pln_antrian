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
                'id_role' => 1, // Asumsikan id_role 1 merujuk ke departemen 'Keuangan' atau lainnya
                'id_departemen' => 1,
            ],
            [
                'username' => 'admin2@email.com',
                'password' => Hash::make('12345'),
                'id_role' => 2, // Asumsikan id_role 2 merujuk ke departemen 'Sumber Daya Manusia'
                'id_departemen' => 2,
            ],
            [
                'username' => 'admin3@email.com',
                'password' => Hash::make('12345'),
                'id_role' => 3, // Asumsikan id_role 3 merujuk ke departemen 'Pemasaran'
                'id_departemen' => 3,
            ],
            [
                'username' => 'admin4@email.com',
                'password' => Hash::make('12345'),
                'id_role' => 4, // Asumsikan id_role 4 merujuk ke departemen 'Teknologi Informasi'
                'id_departemen' => 4,
            ],
            [
                'username' => 'admin5@email.com',
                'password' => Hash::make('12345'),
                'id_role' => 5, // Asumsikan id_role 4 merujuk ke departemen 'Teknologi Informasi'
                'id_departemen' => 5,
            ],
            [
                'username' => 'feby@gmail.com',
                'password' => Hash::make('12345'),
                'id_role' => 6, // Asumsikan id_role 4 merujuk ke departemen 'Teknologi Informasi'
                'id_departemen' => NULL,
            ],
            // Tambahkan data lainnya sesuai kebutuhan
        ]);
    }
}
