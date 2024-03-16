<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Progres extends Model
{
    use HasFactory;

    protected $table = 'progres';
    protected $primaryKey = 'id_progres';
    protected $fillable = ['progres'];

    //no timestamps
    public $timestamps = false;

    public function magang()
    {
        return $this->hasMany(Magang::class, 'id_progres', 'id_progres');
    }
}
