<?php

namespace App\Models;

use App\Casts\Kode;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pasien extends Model
{
    use HasFactory;
    public $timestamps = false;

    public $fillable = [
        'id',
        'nik',
        'layanan',
        'email',
        'nama',
        'jk',
        'status',
        'tempat_lahir',
        'tanggal_lahir',
        'kontak', 
        'alamat',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Accessor untuk generate kode rekam medis
    public function getKodeRMAttribute()
    {
        return 'RM' . str_pad($this->id, 8, '0', STR_PAD_LEFT);
    }

}
