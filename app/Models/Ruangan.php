<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ruangan extends Model
{
    use HasFactory;

    protected $table = 'ruangan';

    protected $fillable = [
        'nama'
    ];

    public function jadwalPropTA()
    {
        return $this->hasMany(JadwalPropTA::class, 'ruangan_id', 'id');
    }
}
