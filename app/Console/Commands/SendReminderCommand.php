<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\RekamMedik;
use App\Jobs\SendReminderWA;
use Carbon\Carbon;

class SendReminderCommand extends Command
{
    protected $signature = 'send:reminder';
    protected $description = 'Kirim pengingat konsultasi ke pasien secara otomatis';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Ambil data rekam medis yang memiliki konsultasi berikut
        $rekamMedikData = RekamMedik::whereNotNull('konsultasi_berikut')->get();

        // Atur waktu saat ini sesuai dengan zona waktu Asia/Jayapura
        $currentTime = Carbon::now('Asia/Jayapura');
        // $currentTime = Carbon::createFromFormat('Y-m-d H:i:s', '2025-01-18 17:00:00', 'Asia/Jayapura');

        foreach ($rekamMedikData as $rekamMedik) {
            // Hitung selisih waktu antara konsultasi berikut dan waktu saat ini
            $timeDifference = $currentTime->diffInHours($rekamMedik->konsultasi_berikut);

            // Kirim reminder jika waktu selisih sesuai dengan kriteria
            if ($timeDifference <= 12 && !$rekamMedik->kirimpesan1) {
                // Kirim pesan pertama jika belum terkirim
                SendReminderWA::dispatch($rekamMedik);
                $rekamMedik->kirimpesan1 = $currentTime;
                $rekamMedik->save();
            } elseif ($timeDifference <= 3 && !$rekamMedik->kirimpesan2) {
                // Kirim pesan kedua jika belum terkirim
                SendReminderWA::dispatch($rekamMedik);
                $rekamMedik->kirimpesan2 = $currentTime;
                $rekamMedik->save();
            }
        }

        $this->info('Pengingat konsultasi telah dikirim!');
    }
}
