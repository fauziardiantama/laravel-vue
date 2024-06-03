<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seminar extends Model
{
    use HasFactory;

    protected $table = 'seminar';
    protected $primaryKey = 'id_seminar';

    protected $fillable = [
        'id_magang',
        'tgl_daftar',
        'tgl_seminar',
        'id_ruangan',
        'judul_kmm',
        'draft_kmm',
        'foto',
        'krs',
        'lembar_revisi',
        'daftar_hadir',
        'selesai_kmm',
        'status'
    ];

    public $timestamps = false;

    public function magang()
    {
        return $this->belongsTo(Magang::class, 'id_magang', 'id_magang');
    }

    public function penguji()
    {
        return $this->belongsToMany(Dosen::class, 'penguji_seminar', 'id_seminar', 'id_dosen');
    }

    public function ruangan()
    {
        return $this->belongsTo(Ruangan::class, 'id_ruangan', 'id');
    }
}
