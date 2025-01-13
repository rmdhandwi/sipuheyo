<?php

use App\Jobs\ProcessJadwalBerobat;
use App\Models\RekamMedik;
use App\services\RekamMedikService;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

// Artisan::command('inspire', function (RekamMedikService $rekamMedikService) {

//    $rekamMedikService->infoKunjunganBerikut();

// })->purpose('Display an inspiring quote')-> everyTenSeconds();


Artisan::command('wa', function (RekamMedikService $rekamMedikService) {
   $rekamMedikService->infoKunjunganBerikut();
})->everyTenMinutes();