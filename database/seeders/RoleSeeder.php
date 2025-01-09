<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
            [
                'nama' => 'Pemasaran',
            ],
            [
                'nama' => 'Niaga',
            ],
            [
                'nama' => 'Konstruksi',
            ],
            [
                'nama' => 'Jaringan',
            ],
            [
                'nama' => 'TE',
            ],
            [
                'nama' => 'Pengguna',
            ],

        ]);
    }
}
