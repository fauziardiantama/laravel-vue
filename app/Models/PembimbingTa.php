<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembimbingTa extends Model
{
    use HasFactory;

    protected $table = 'pembimbing_ta';

    protected $fillable = [
        'id_magang',
        'proposal_ta_id'
    ];

    public function magang()
    {
        return $this->belongsTo(Magang::class, 'id_magang', 'id_magang');
    }

    //get dosen from magang->dosen()
    public function dosen()
    {
        return $this->magang();
    }

    public function proposalTa()
    {
        return $this->belongsTo(ProposalTa::class, 'proposal_ta_id', 'id');
    }
}
