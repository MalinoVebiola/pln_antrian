<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartemenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('departemens')->insert([
            [
                'nama_departemen' => 'Pemasaran',
            ],
            [
                'nama_departemen' => 'Niaga',
            ],
            [
                'nama_departemen' => 'Konstruksi',
            ],
            [
                'nama_departemen' => 'Jaringan',
            ],
            [
                'nama_departemen' => 'TE',
            ],

        ]);
    }
}
