<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class Admin extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    //database table name "admin"
    protected $table = 'admin';
    //primary key "id_admin"
    protected $primaryKey = 'id_admin';

    //fillable fields
    protected $fillable = [
        'nama',
        'email',
        'password'
    ];

    //hidden fields
    protected $hidden = [
        'password'
    ];

    //timestamps false
    public $timestamps = false;

    public function isAdmin()
    {
        return true;
    }
    public function isDosen()
    {
        return false;
    }
    public function isMahasiswa()
    {
        return false;
    }
}
