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
        'rekammedik',
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

    public static function generateKodeRekamMedik()
    {
        $prefix = 'PBY'; // Kode Puskesmas Hebeybhulu Yoka
        $year = date('Y'); // Tahun saat ini
        $month = date('m'); // Bulan saat ini

        // Cari kode terakhir yang memiliki format PBY-YYYY-MM-XXXX
        $lastRecord = self::where('rekammedik', 'like', "PBY-{$year}-{$month}-%")
            ->orderBy('rekammedik', 'desc')
            ->first();


        if ($lastRecord) {
            // Ambil nomor urut terakhir dari kode
            $lastNumber = intval(substr($lastRecord->rekammedik, -4)) + 1;
        } else {
            // Jika belum ada data bulan ini, mulai dari 0001
            $lastNumber = 1;
        }

        // Format nomor urut menjadi 4 digit (0001, 0002, dst)
        $formattedNumber = str_pad($lastNumber, 4, '0', STR_PAD_LEFT);

        return "{$prefix}-{$year}-{$month}-{$formattedNumber}";
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
