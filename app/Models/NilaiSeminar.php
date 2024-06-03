<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NilaiSeminar extends Model
{
    use HasFactory;

    protected $table = 'nilai_seminar';
    protected $primaryKey = 'id_nilai_seminar';

    protected $fillable = [
        'id_magang',
        'id_parameter',
        'nilai',
        'id_dosen'
    ];

    public $timestamps = false;
    
    public function Magang()
    {
        return $this->belongsTo(Magang::class, 'id_magang', 'id_magang');
    }

    public function ParameterNilaiSeminar()
    {
        return $this->belongsTo(ParameterNilaiSeminar::class, 'id_parameter', 'id_parameter');
    }

    public function Dosen()
    {
        return $this->belongsTo(Dosen::class, 'id_dosen', 'id_dosen');
    }
}
