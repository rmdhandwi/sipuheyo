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
        'dokter_id',
        'pegawai_id'
    ];

    public static function generateKode($nama)
    {
        // Hapus simbol dan karakter non-alfanumerik dari nama
        $cleanedNama = preg_replace('/[^a-zA-Z0-9\s]/', '', strtoupper($nama)); // Nama dalam huruf besar

        // Split nama menjadi array kata
        $words = explode(' ', $cleanedNama);

        // Ambil huruf pertama setiap kata
        $initials = implode('', array_map(fn($word) => $word[0] ?? '', $words));

        // Cari kode yang sudah ada dengan nama yang sama persis
        $existingKodeForName = self::where('nama', $nama)
            ->orderBy('kode', 'desc')
            ->pluck('kode')
            ->first();

        if ($existingKodeForName) {
            // Jika nama yang sama ditemukan, gunakan awalan kode yang sama
            $kode = substr($existingKodeForName, 0, strlen($initials));
        } else {
            // Jika nama belum ada, gunakan awalan berdasarkan huruf pertama
            $kode = $initials;

            // Periksa konflik kode awal pada nama yang berbeda
            $isConflict = self::where('kode', 'LIKE', "$kode%")->exists();

            if ($isConflict) {
                // Jika ada konflik, tambahkan huruf kedua dari setiap kata
                $kode = implode('', array_map(fn($word) => substr($word, 0, 2), $words));
            }
        }

        // Cek kode terakhir dengan awalan yang sama
        $lastKode = self::where('kode', 'LIKE', "$kode%")
        ->orderBy('kode', 'desc')
        ->pluck('kode')
        ->first();

        // Tentukan angka berikutnya
        $nextNumber = 1; // Default angka awal
        if ($lastKode) {
            $lastNumber = (int) substr($lastKode, strlen($kode)); // Ambil bagian numerik
            $nextNumber = $lastNumber + 1; // Lanjutkan penomoran jika sudah ada
        }

        // Gabungkan kode dengan angka unik 2 digit (misalnya: PG01, PG02)
        return $kode . str_pad($nextNumber, 2, '0', STR_PAD_LEFT);
    }



    // protected function casts(): array
    // {
    //     return [
    //        // 'kode' => Kode::class,
    //     ];
    // }

    public function dokter()
    {
        return  $this->belongsTo(Dokter::class, 'dokter_id', 'id');
    }

    public function pegawai(): HasOne
    {
        return  $this->hasOne(Pegawai::class, 'id', "pegawai_id");
    }
}
