<?php

namespace App\Models;

use App\Casts\Kode;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Poli extends Model
{
    use HasFactory;

    public $timestamps = false;

    public $fillable = [
        'id',
        'kode',
        'nama',
        'penyakit',
        'keterangan',
        'jenis',
        'dokter_id',
        'pegawai_id'
    ];


    protected function casts(): array
    {
        return [
           // 'kode' => Kode::class,
        ];
    }

    public function dokter(){
        return  $this->belongsTo(Dokter::class, 'dokter_id', 'id');
    }

    public function pegawai():HasOne{
        return  $this->hasOne(Pegawai::class,'id',"pegawai_id");
    }

}
