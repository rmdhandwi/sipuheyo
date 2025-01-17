<?php

namespace App\Models;

use App\Casts\Kode;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Pegawai extends Model
{
    use HasFactory;
    public $timestamps = false;

    public $fillable = [
        'id',
        'nama',
        'nip',
        'jk',
        'email',
        'bagian',
        'kontak',
        'user_id'
    ];

    // protected function casts(): array
    // {
    //     return [
    //         'kode' => Kode::class,
    //     ];
    // }
}
