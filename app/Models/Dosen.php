<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class Dosen extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'dosen';
    protected $primaryKey = 'id_dosen';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nik',
        'nama',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];
    
    //timestamps false
    public $timestamps = false;

    public function auth()
    {
        return $this->hasOne(AuthUser::class, 'id_dosen', 'id_dosen');
    }

    public function topik()
    {
        return $this->belongsToMany(TopikKmm::class, 'dosen_topik', 'id_dosen', 'id_topik');
    }

    public function magang()
    {
        return $this->hasMany(Magang::class, 'id_dosen', 'id_dosen');
    }

    public function jadwalPropTA()
    {
        return $this->belongsToMany(JadwalPropTA::class, 'jadwal_proposal_ta_dosen', 'dosen_id', 'jadwal_proposal_ta_id');
    }
}
