<?php

namespace App\Observers;

use App\Models\RekamMedik;
use App\Jobs\SendReminderWA;
use Carbon\Carbon;

class RekamMedikObserver
{
    /**
     * Handle the RekamMedik "created" event.
     *
     * @param  \App\Models\RekamMedik  $rekamMedik
     * @return void
     */
    public function created(RekamMedik $rekamMedik)
    {
        // Cek jika data 'konsultasi_berikut' terisi
        if ($rekamMedik->konsultasi_berikut) {
            // Jalankan job untuk mengirim pesan WA
            SendReminderWA::dispatch($rekamMedik);
        }
    }

    /**
     * Handle the RekamMedik "updated" event.
     *
     * @param  \App\Models\RekamMedik  $rekamMedik
     * @return void
     */
    public function updated(RekamMedik $rekamMedik)
    {
        // Cek jika data 'konsultasi_berikut' terisi setelah update
        if ($rekamMedik->isDirty('konsultasi_berikut') && $rekamMedik->konsultasi_berikut) {
            // Jalankan job untuk mengirim pesan WA
            SendReminderWA::dispatch($rekamMedik);
        }
    }
}
