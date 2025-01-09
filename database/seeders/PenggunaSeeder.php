<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PenggunaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        // Insert data pengguna
        DB::table('penggunas')->insert([
            [
                'no_ktp' => '1234567890123456',
                'nama' => 'Pelanggan Satu',
                'alamat' => 'Jl. Mawar No. 1',
                'no_hp' => '081234567890',
                'no_npwp' => '123456789012345',
                'no_rekening' => '1234567890',
                'tarif_daya' => 'R1/450',
                'id_akun' => 6,
            ],
        ]);
    }
}
