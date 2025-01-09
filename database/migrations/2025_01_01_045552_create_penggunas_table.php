<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('penggunas', function (Blueprint $table) {
            $table->id('id_pelanggan'); // ID pelanggan sebagai primary key
            $table->string('no_ktp')->unique(); // Nomor KTP, harus unik
            $table->string('nama'); // Nama pelanggan
            $table->text('alamat'); // Alamat pelanggan
            $table->string('no_hp')->unique(); // Nomor HP, harus unik
            $table->string('no_npwp')->nullable(); // Nomor NPWP, opsional
            $table->string('no_rekening')->nullable(); // Nomor rekening, opsional
            $table->string('tarif_daya'); // Tarif atau daya
            $table->unsignedBigInteger('id_akun'); // Foreign key ke tabel Akun

            // Definisi foreign key ke tabel Akun
            $table->foreign('id_akun')->references('id_akun')->on('akuns')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('penggunas');
    }
};
