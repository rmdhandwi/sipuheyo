<?php

namespace App\Models;

use App\Casts\Kode;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Obat extends Model
{
    use HasFactory;

    public $timestamps=false;

    public $fillable =[
        'id',
        'nama',
        'merek',
        'dosis',
        'deskripsi',
        'kemasan',
        'exp'
    ];
}
