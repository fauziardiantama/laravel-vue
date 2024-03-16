<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SesiUjian extends Model
{
    use HasFactory;

    protected $table = 'sesi_ujian';

    protected $fillable = [
        'no_sesi',
        'waktu_mulai',
        'waktu_selesai'
    ];

    public function jadwalPropTA()
    {
        return $this->hasMany(JadwalPropTA::class, 'sesi_id', 'id');
    }
}
