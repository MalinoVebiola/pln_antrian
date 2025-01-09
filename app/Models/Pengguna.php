<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengguna extends Model
{
    use HasFactory;

    protected $table = 'penggunas'; 
    protected $primaryKey = 'id_pelanggan'; // Primary key
    public $timestamps = false; // Nonaktifkan timestamps

    protected $fillable = [
        'no_ktp',
        'nama',
        'alamat',
        'no_hp',
        'no_npwp',
        'no_rekening',
        'tarif_daya',
        'id_akun',
    ];

    public function antrian()
    {
        return $this->hasMany(Antrian::class, 'id_pelanggan', 'id_pelanggan');
    }

    public function akun()
    {
        return $this->belongsTo(Akun::class, 'id_akun'); // Sesuaikan dengan nama kolom foreign key
    }

}


