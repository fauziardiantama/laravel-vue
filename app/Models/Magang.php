<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Magang extends Model
{
    use HasFactory;

    protected $table = 'magang';
    protected $primaryKey = 'id_magang';

    protected $fillable = [
        'nim',
        'tahun',
        'id_topik',
        'id_instansi',
        'status_pengajuan_instansi',
        'status_diterima_instansi',
        'id_dosen',
        'status_dosen',
        'id_progres'
    ];

    public $timestamps = false;

    public function topik()
    {
        return $this->belongsTo(TopikKmm::class, 'id_topik', 'id_topik');
    }

    public function instansi()
    {
        return $this->belongsTo(Instansi::class, 'id_instansi', 'id_instansi');
    }

    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'id_dosen', 'id_dosen');
    }

    public function progres()
    {
        return $this->belongsTo(Progres::class, 'id_progres', 'id_progres');
    }

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'nim', 'nim');
    }

    public function tahun()
    {
        return $this->belongsTo(Tahun::class, 'tahun', 'tahun');
    }

    public function rencanaMagang()
    {
        return $this->hasMany(RencanaMagang::class, 'id_magang', 'id_magang');
    }

    public function pembimbingTa()
    {
        return $this->hasMany(ProposalTA::class, 'id_magang', 'id_magang');
    }

    public function suratMagang()
    {
        return $this->hasMany(SuratMagang::class, 'id_magang', 'id_magang');
    }

    public function suratJawaban()
    {
        return $this->hasMany(SuratJawaban::class, 'id_magang', 'id_magang');
    }
}
