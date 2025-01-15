<?php

use App\Models\Poli;
use App\Models\Dokter;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use App\services\RekamMedikService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DokterController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/poli/{id}', function (Request $request, $userid) {
    $pegawai = Pegawai::where('user_id', $userid)->first();
    $poli = Poli::where('pegawai_id', $pegawai->id)->first();
    return $poli;
});


Route::get('/jadwalberobat/{poliid}/{date}', function (RekamMedikService $rekamMedik, $poliid, $date) {
    $result =  $rekamMedik->getByPoliAndDate($poliid, $date);
    return $result;
});
Route::get('/rekammedik/{poliid}/{date}', function (RekamMedikService $rekamMedik, $poliid, $date) {
    $result =  $rekamMedik->getByPoliAndTanggal($poliid, $date);
    return $result;
});


Route::get('/rekammedik/all', function (RekamMedikService $rekamMedik) {
    $result =  $rekamMedik->all()->toJson();
    return $result;
});

Route::get('/dokter/jadwalberobatbydate/{dokterId}/{date}', [DokterController::class, 'jadwalberobatByDate'])->name('admin.jadwalberobatByDate');
