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
        Schema::create('laporans', function (Blueprint $table) {
            $table->id(); // id as primary key
            $table->date('tanggal_laporan'); // report date column
            $table->string('bidang_keluhan'); // complaint field column
            $table->text('detail_keluhan'); // complaint details column
            $table->string('layanan_via'); // service via column
            $table->string('status')->nullable();
            $table->string('surat')->nullable();
           $table->boolean('is_hidden')->default(false);
            $table->unsignedBigInteger('id_antrian'); // foreign key to antrian table
            $table->timestamps(); // timestamps for created_at and updated_at

            // Adding foreign key constraint
            $table->foreign('id_antrian')->references('id_antrian')->on('antrians')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Dropping foreign key before dropping the table
        Schema::table('laporans', function (Blueprint $table) {
            $table->dropForeign(['id_antrian']);
        });

        Schema::dropIfExists('laporans');
    }
};
