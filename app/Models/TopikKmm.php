<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TopikKmm extends Model
{
    use HasFactory;

    protected $table = 'topik_kmm';
    //primary key id_topik
    protected $primaryKey = 'id_topik';
    protected $fillable = [
        'nama_topik'
    ];

    //no timestamps
    public $timestamps = false;

    //one to many relationship with magang
    public function magang()
    {
        return $this->hasMany(Magang::class, 'id_topik', 'id_topik');
    }

    //many to many relationship with dosen
    public function dosen()
    {
        return $this->belongsToMany(Dosen::class, 'dosen_topik', 'id_topik', 'id_dosen');
    }
}
