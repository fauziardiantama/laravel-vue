<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Informasi extends Model
{
    use HasFactory;

    protected $table = 'informasi';
    protected $primaryKey = 'id_informasi';
    protected $fillable = [
        'tanggal',
        'judul',
        'deskripsi',
        'dokumen',
        'status_publikasi'
    ];

    public $timestamps = false;
}
