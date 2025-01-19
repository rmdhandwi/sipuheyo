<?php

namespace App\Jobs;

use App\Models\RekamMedik;
use App\Services\WAService;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendReminderWA   implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $rekamMedik;

    public function __construct(RekamMedik $rekamMedik)
    {
        $this->rekamMedik = $rekamMedik;
    }

    public function handle()
    {
        // Set locale Carbon ke bahasa Indonesia
        Carbon::setLocale('id');

        // Ambil data pasien, poli, dan dokter dari rekam medis
        $rekamMedik = $this->rekamMedik;

        $pasien = $rekamMedik->pasien;   // Mengambil data pasien (nama, kontak)
        $poli = $rekamMedik->poli;       // Mengambil data poli (penyakit)
        $dokter = $rekamMedik->dokter;   // Mengambil data dokter (nama)

        // Format tanggal dan waktu dengan menggunakan isoFormat (lebih baik daripada formatLocalized)
        $formattedDate = Carbon::parse($rekamMedik->konsultasi_berikut)->isoFormat('dddd, D MMMM YYYY'); // Format tanggal dengan nama bulan Indonesia
        $formattedWaktu = Carbon::parse($rekamMedik->konsultasi_berikut)->isoFormat('HH:mm'); // Format waktu (jam dan menit)

        // Format pesan sesuai permintaan
        $pesan = "🌟 *Pengingat Konsultasi* 🌟\n\n"
        . "Bapak/Ibu *{$pasien->nama}*,\n\n"
        . "Kami dari *PUSKESMAS HEBEYBHULU YOKA* ingin mengingatkan kembali tentang jadwal konsultasi pemeriksaan penyakit *{$poli->penyakit}* di *{$poli->nama}*.\n\n"
        . "Konsultasi bersama dokter *{$dokter->nama}* akan dilakukan pada:\n"
        . "*Tanggal:* *{$formattedDate}*\n"
        . "*Jam:* *{$formattedWaktu} WIT*\n\n"
        . "🔔 Harap hadir tepat waktu. Terimakasih atas perhatian Anda.\n\n"
        . "🙏 Semoga sehat selalu!";

        // Kirim pesan menggunakan WAService
        WAService::sendMessage($pasien->kontak, $pesan);
    }

}
