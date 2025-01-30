<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class SendReminderWA implements ShouldQueue
{
    // use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    // protected $rm;
    // protected $tipePesan;

    // /**
    //  * Create a new job instance.
    //  */
    // public function __construct($rm, $tipePesan)
    // {
    //     $this->rm = $rm;
    //     $this->tipePesan = $tipePesan;
    // }

    // /**
    //  * Execute the job.
    //  */
    // public function handle(): void
    // {
    //     try {
    //         $rm = $this->rm;
    //         $pasien = $rm->pasien;
    //         $dokter = $rm->dokter;
    //         $poli = $rm->poli;

    //         // Format nomor WhatsApp ke +62 jika belum sesuai
    //         $kontak = $pasien->kontak;
    //         if (substr($kontak, 0, 1) === '0') {
    //             $kontak = '+62' . substr($kontak, 1);
    //         }

    //         // Format tanggal dan waktu konsultasi
    //         $formattedDate = Carbon::parse($rm->konsultasi_berikut, 'Asia/Jayapura')->isoFormat('dddd, D MMMM YYYY');
    //         $formattedTime = Carbon::parse($rm->konsultasi_berikut, 'Asia/Jayapura')->isoFormat('HH:mm');

    //         // Pesan pengingat
    //         $pesanTambahan = ($this->tipePesan == 1)
    //             ? "🔔 Ini adalah pengingat 12 jam sebelum konsultasi."
    //             : "⏳ Ini adalah pengingat terakhir 3 jam sebelum konsultasi.";

    //         $pesan = "🌟 *Pengingat Konsultasi* 🌟\n\n"
    //             . "Bapak/Ibu *{$pasien->nama}*,\n\n"
    //             . "Kami dari *PUSKESMAS HEBEYBHULU YOKA* ingin mengingatkan kembali tentang jadwal konsultasi pemeriksaan *{$poli->penyakit}* di *{$poli->nama}*.\n\n"
    //             . "🩺 *Dokter:* {$dokter->nama}\n"
    //             . "📅 *Tanggal:* {$formattedDate}\n"
    //             . "🕒 *Jam:* {$formattedTime} WIT\n\n"
    //             . "{$pesanTambahan}\n\n"
    //             . "🙏 Semoga sehat selalu!";

    //         // Kirim pesan ke Zenziva API
    //         $data = [
    //             "userkey" => env('ZIVA_USERKEY', ''),
    //             "passkey" => env('ZIVA_PASSKEY'),
    //             "to" => $kontak,
    //             "message" => $pesan
    //         ];

    //         // Delay agar tidak dianggap bulk/broadcast
    //         sleep(5);

    //         $response = Http::post('https://console.zenziva.net/wareguler/api/sendWA/', $data);

    //         if ($response->successful()) {
    //             Log::info("✅ Pesan WA berhasil dikirim ke {$kontak} ({$pasien->nama})");
    //         } else {
    //             Log::error("❌ Gagal mengirim pesan ke {$kontak}: " . json_encode($response->json()));
    //         }
    //     } catch (\Throwable $th) {
    //         Log::error("❌ Error dalam SendWhatsAppMessage Job: " . $th->getMessage());
    //     }
    // }
}
