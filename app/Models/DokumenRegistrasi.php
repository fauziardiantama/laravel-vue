<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DokumenRegistrasi extends Model
{
    use HasFactory;

    protected $table = 'dokumen_registrasi';
    protected $primaryKey = 'id_dokumen_registrasi';
    protected $fillable = [
        'nim',
        'krs',
        'transkrip',
        'bukti_seminar',
        'kartu_mahasiswa'
    ];

    //timestamps false
    public $timestamps = false;

    public function isEmpty()
    {
        return $this->krs == null && $this->transkrip == null && $this->bukti_seminar == null && $this->kartu_mahasiswa == null;
    }

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'nim', 'nim');
    }
}
