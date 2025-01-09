<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Akun extends Authenticatable
{
    use HasFactory;

    protected $table = 'akuns';
    protected $primaryKey = 'id_akun';
    protected $fillable = ['username', 'password', 'id_role'];
    public $timestamps = false; // Nonaktifkan timestamps

    public function role()
    {
        return $this->belongsTo(role::class, 'id_role', 'id_role');
    }

    
}

