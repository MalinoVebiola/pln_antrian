<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class  extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('departemens', function (Blueprint $table) {
            $table->id(); // Kolom ID (auto-increment primary key)
            $table->string('nama_departemen'); // Kolom nama departemen
            $table->timestamps(); // Kolom created_at dan updated_at
        });
    }

};
