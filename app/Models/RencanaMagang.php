<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RencanaMagang extends Model
{
    use HasFactory;

    protected $table = 'rencana_magang';
    protected $primaryKey = 'id_rencana';

    protected $fillable = [
        'id_magang',
        'minggu',
        'kegiatan'
    ];

    public $timestamps = false;

    public function magang()
    {
        return $this->belongsTo(Magang::class, 'id_magang', 'id_magang');
    }
}
