<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengujiSeminar extends Model
{
    use HasFactory;

    protected $table = 'penguji_seminar';

    protected $fillable = [
        'id_seminar',
        'id_dosen'
    ];
}
