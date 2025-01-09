<?php

// app/Models/Antrian.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Antrian extends Model
{
    use HasFactory;

    protected $table = 'antrians';
    protected $primaryKey = 'id_antrian';
    protected $fillable = ['tanggal', 'nomor_antrian', 'status', 'id_pelanggan']; // Tambahkan 'tanggal'

    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'id_pelanggan', 'id_pelanggan'); // Sesuaikan nama kolom jika perlu
    }

    public function laporans()
    {
        return $this->hasMany(Laporan::class, 'id_antrian', 'id_antrian');
    }
}
