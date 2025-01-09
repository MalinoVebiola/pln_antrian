<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Laporan extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'tanggal_laporan',
        'bidang_keluhan',
        'detail_keluhan',
        'layanan_via',
        'status',
        'id_antrian',
        'surat', // Include pdf_file field
    ];

    // Define relationships if needed (e.g., with Antrian)
    public function antrian()
    {
        return $this->belongsTo(Antrian::class, 'id_antrian');
    }
}
