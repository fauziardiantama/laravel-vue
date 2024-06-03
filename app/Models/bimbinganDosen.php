<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BimbinganDosen extends Model
{
    use HasFactory;

    protected $table = 'bimbingan_dosen';
    protected $primaryKey = 'id_bimbingan_dosen';
    protected $fillable = [
        'id_magang',
        'tanggal',
        'data_bimbingan',
        'status'
    ];

    //no timestamps
    public $timestamps = false;

    public function magang()
    {
        return $this->belongsTo(magang::class, 'id_magang', 'id_magang');
    }
}
