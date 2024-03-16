<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratMagang extends Model
{
    use HasFactory;

    protected $table = 'surat_magang';
    protected $primaryKey = 'id_surat';

    protected $fillable = [
        'id_magang',
        'no_urut',
        'jenis_surat',
        'nomor_surat',
        'file_surat',
    ];

    protected $appends = [
        'jenis_surat_name'
    ];

    public $timestamps = false;

    public function magang()
    {
        return $this->belongsTo(Magang::class, 'id_magang', 'id_magang');
    }

    public function getJenisSuratNameAttribute()
    {
        return $this->jenis_surat == 1 ? 'Surat Pengantar' : 'Surat Serah Terima';
    }
}
