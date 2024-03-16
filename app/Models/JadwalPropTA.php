<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalPropTA extends Model
{
    use HasFactory;

    protected $table = 'jadwal_proposal_ta';
    
    protected $fillable = [
        'tanggal',
        'tahun',
        'semester_id',
        'sesi_id',
        'ruangan_id'
    ];

    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }

    public function sesiUjian()
    {
        return $this->belongsTo(SesiUjian::class, 'sesi_id', 'id');
    }

    public function ruangan()
    {
        return $this->belongsTo(Ruangan::class);
    }

    public function proposalTA()
    {
        //many to many ProposalTA with pivot JadwalPropTAMahasiswa
        return $this->belongsToMany(ProposalTA::class, 'jadwal_proposal_ta_mahasiswa', 'jadwal_proposal_ta_id', 'proposal_ta_id');
    }

    public function Dosen()
    {
        return $this->belongsToMany(Dosen::class, 'jadwal_proposal_ta_dosen', 'jadwal_proposal_ta_id', 'dosen_id');
    }
}
