<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParameterNilaiInstansi extends Model
{
    use HasFactory;

    protected $table = 'parameter_nilai_instansi';
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
}
