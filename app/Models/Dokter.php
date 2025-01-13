<?php

namespace App\Models;

use App\Casts\Kode;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dokter extends Model
{
    use HasFactory;

    public $timestamps = false;

    public $fillable = [
        'id',
        'nid',
        'nama',
        'email',
        'spesialis',
        'kontak',
        'jk',
        'user_id',
    ];

    public function poli()
    {
        return $this->hasMany(Poli::class, 'dokter_id', 'id');
    }
}
