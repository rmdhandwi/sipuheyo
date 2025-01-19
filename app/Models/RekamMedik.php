<?php

namespace App\Models;

use App\Casts\Kode;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class RekamMedik extends Model
{
    use HasFactory;

    // protected $PrimaryKey = 'id';
    public $fillable = [
        'id',
        'antrian',
        'tanggal',
        'poli_id',
        'pasien_id',
        'dokter_id',
        'konsultasi_berikut',
        'keluhan',
        'status',
        'resep',
        'resep_manual',
        'penanganan',
        'hasil_lab',
        'kirimpesan1',
        'kirimpesan2',
    ];

    protected function casts(): array
    {
        return [
            'kode' => Kode::class,
            'konsultasi_berikut' => 'date:Y-m-d H:s',
            'kirimpesan1' => 'datetime',
            'kirimpesan2' => 'datetime',
        ];
    }

    public function dokter(): HasOne
    {
        return  $this->hasOne(Dokter::class, 'id', 'dokter_id');
    }

    public function poli(): HasOne
    {
        return $this->hasOne(Poli::class, 'id', 'poli_id');
    }

    public function pasien(): HasOne
    {
        return $this->hasOne(Pasien::class, 'id', 'pasien_id');
    }
}
