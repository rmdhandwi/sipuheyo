<?php

use App\Http\Controllers\DokterController;
use App\services\RekamMedikService;
use Illuminate\Support\Facades\Route;



Route::group(['middleware' => 'role:dokter'], function () {
    Route::get('/dokter', [DokterController::class, 'index'])->name('dokter.index');
    Route::get('/dokter/jadwalberobat', [DokterController::class, 'jadwalberobat'])->name('admin.jadwalberobat');
 

    include 'dokter-pasien.php';
    include 'dokter-rekammedik.php';
});

