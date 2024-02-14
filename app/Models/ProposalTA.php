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
}
