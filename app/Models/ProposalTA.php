<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProposalTA extends Model
{
    use HasFactory;
    protected $table = 'proposal_ta';
    
    protected $fillable = [
        'judul_proposal',
        'file_proposal',
        'status_proposal',
        'nim',
        'tahun',
        'semester_id',
        'token',
        'token_expired'
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'nim', 'nim');
    }

    public function tahun()
    {
        return $this->belongsTo(Tahun::class, 'tahun', 'tahun');
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class, 'semester_id', 'id');
    }

    public function jadwalPropTA()
    {
        return $this->belongsToMany(JadwalPropTA::class, 'jadwal_proposal_ta_mahasiswa', 'proposal_ta_id', 'jadwal_proposal_ta_id');
    }

    public function pembimbingTa()
    {
        return $this->belongsToMany(Magang::class, 'pembimbing_ta', 'proposal_ta_id', 'id_magang');
    }
}
