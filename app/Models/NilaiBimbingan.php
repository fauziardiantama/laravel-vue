<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NilaiBimbingan extends Model
{
    use HasFactory;

    protected $table = 'nilai_bimbingan';
    protected $primaryKey = 'id_nilai_bimbingan';

    protected $fillable = [
        'id_magang',
        'id_parameter',
        'nilai',
    ];

    public $timestamps = false;

    public function Magang()
    {
        return $this->belongsTo(Magang::class, 'id_magang', 'id_magang');
    }

    public function ParameterNilaiBimbingan()
    {
        return $this->belongsTo(ParameterNilaiBimbingan::class, 'id_parameter', 'id_parameter');
    }
}
