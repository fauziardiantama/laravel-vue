<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalPropTAMahasiswa extends Model
{
    use HasFactory;

    protected $table = 'jadwal_proposal_ta_mahasiswa';

    protected $fillable = [
        'jadwal_proposal_ta_id',
        'proposal_ta_id'
    ];
}
