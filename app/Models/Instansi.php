<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Instansi extends Model
{
    use HasFactory;

    protected $table = 'instansi';
    protected $primaryKey = 'id_instansi';

    protected $fillable = [
        'nama',
        'email',
        'status_email',
        'password',
        'token',
        'tgl_generate',
        'alamat',
        'no_telp',
        'web',
        'status_instansi'
    ];

    //timestamps false
    public $timestamps = false;

    public function magang()
    {
        return $this->hasMany(Magang::class, 'id_instansi', 'id_instansi');
    }
}
