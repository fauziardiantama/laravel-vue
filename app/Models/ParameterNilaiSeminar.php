<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParameterNilaiSeminar extends Model
{
    use HasFactory;

    protected $table = 'parameter_nilai_seminar';
    protected $primaryKey = 'id_parameter';

    protected $fillable = [
        'tahun',
        'parameter',
    ];

    public $timestamps = false;

    public function Tahun()
    {
        return $this->belongsTo(Tahun::class, 'tahun', 'tahun');
    }

    public function NilaiSeminar()
    {
        return $this->hasMany(NilaiSeminar::class, 'id_parameter', 'id_parameter');
    }
}
