<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAntriansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('antrians', function (Blueprint $table) {
            $table->bigIncrements('id_antrian'); // Primary key
            $table->date('tanggal'); // Tanggal antrian
            $table->string('nomor_antrian', 50); // Nomor antrian
            $table->boolean('status')->default(0); // Default bisa disesuaikan
            $table->unsignedBigInteger('id_pelanggan'); // Foreign key untuk pengguna
            $table->timestamps(); // Created at & Updated at

            // Relasi ke tabel pengguna
            $table->foreign('id_pelanggan')->references('id_pelanggan')->on('penggunas')->onDelete('cascade');
        });
    }


}
