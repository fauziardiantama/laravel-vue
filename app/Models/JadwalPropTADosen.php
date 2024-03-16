<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalPropTADosen extends Model
{
    use HasFactory;

    protected $table = 'jadwal_proposal_ta_dosen';

    protected $fillable = [
        'jadwal_proposal_ta_id',
        'dosen_id'
    ];
}
