<?php


namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;


class Mahasiswa extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'mahasiswa';
    protected $primaryKey = 'nim';
    public $incrementing = false;
    protected $keyType = 'string';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nim',
        'nama',
        'email',
        'password',
        'no_telp',
        'status'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password'
    ];
    
    //timestamps false
    public $timestamps = false;

    public function auth()
    {
        return $this->hasOne(AuthUser::class, 'nim', 'nim');
    }

    public function dokumenRegistrasi()
    {
        return $this->hasOne(DokumenRegistrasi::class, 'nim', 'nim');
    }

    public function proposalTA()
    {
        return $this->hasOne(ProposalTA::class, 'nim', 'nim');
    }
}
