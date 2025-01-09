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
        Schema::create('akuns', function (Blueprint $table) {
            $table->id('id_akun');
            $table->string('username')->unique();
            $table->string('password');
            $table->unsignedBigInteger('id_role')->nullable();
            $table->unsignedBigInteger('id_departemen')->nullable(); // Kolom untuk relasi ke tabel departemens

            // Definisi foreign key ke tabel role
            $table->foreign('id_role')->references('id_role')->on('roles')->onDelete('cascade');

            // Definisi foreign key ke tabel departemens
            $table->foreign('id_departemen')->references('id')->on('departemens')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('akuns');
    }
};
