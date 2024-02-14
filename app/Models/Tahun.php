<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tahun extends Model
{
    use HasFactory;
    protected $table = 'tahun';
    protected $primaryKey = 'tahun';

    protected $fillable = [
        'tahun'
    ];
    
    public $timestamps = false;

    public function proposalTA()
    {
        return $this->hasMany(ProposalTA::class, 'tahun', 'tahun');
    }
}
