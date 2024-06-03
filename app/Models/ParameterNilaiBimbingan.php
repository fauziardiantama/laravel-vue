<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParameterNilaiBimbingan extends Model
{
    use HasFactory;

    protected $table = 'parameter_nilai_bimbingan';
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

    public function NilaiBimbingan()
    {
        return $this->hasMany(NilaiBimbingan::class, 'id_parameter', 'id_parameter');
    }
}
