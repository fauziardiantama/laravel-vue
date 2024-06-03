<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NilaiAkhir extends Model
{
    use HasFactory;

    protected $table = 'nilai_akhir';
    protected $primaryKey = 'id_nilai_akhir';

    protected $fillable = [
        'id_magang',
        'nilai_akhir',
    ];

    public $timestamps = false;

    public function Magang()
    {
        return $this->belongsTo(Magang::class, 'id_magang', 'id_magang');
    }
}
