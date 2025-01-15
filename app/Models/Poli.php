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
        $cleanedNama = preg_replace('/[^a-zA-Z0-9\s]/', '', strtoupper($nama));

        // Split nama menjadi array kata
        $words = explode(' ', $cleanedNama);

        // Ambil huruf pertama setiap kata
        $initials = implode('', array_map(fn($word) => $word[0] ?? '', $words));

        $kode = $initials; // Awal kode berdasarkan huruf pertama
        $isConflict = true; // Flag untuk mengecek konflik

        $iteration = 1; // Iterasi untuk menambahkan huruf jika terjadi konflik

        while ($isConflict) {
            // Cek apakah ada kode yang sudah digunakan
            $existingKode = self::where('kode', 'LIKE', "$kode%")->first();

            if (!$existingKode || $existingKode->nama === $nama) {
                // Jika tidak ada konflik atau kode sesuai nama yang sama, hentikan iterasi
                $isConflict = false;
            } else {
                // Jika konflik, tambahkan huruf berikutnya dari setiap kata
                $kode = implode('', array_map(fn($word) => substr($word, 0, $iteration), $words));
                $iteration++;
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
            $nextNumber = $lastNumber + 1;
        }

        // Gabungkan kode dengan angka unik 3 digit
        return $kode . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
    }


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
