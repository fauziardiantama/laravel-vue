<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Semester extends Model
{
    use HasFactory;

    protected $table = 'semester';
    protected $fillable = ['semester'];

    public function proposalTA()
    {
        return $this->hasMany(ProposalTA::class);
    }

    public function jadwalPropTA()
    {
        return $this->hasMany(JadwalPropTA::class);
    }
}
