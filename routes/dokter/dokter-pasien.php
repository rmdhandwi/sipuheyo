<?php

use App\Models\Dokter;
use App\Models\Pasien;
use App\Models\Poli;
use App\services\DokterService;
use App\services\PasienService;
use App\services\RekamMedikService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/dokter/pasien', function (RekamMedikService $rekammedikService) {
    $user = Auth::user();
    $dokter = Dokter::where("user_id", $user->id)->first();
    $poli = Poli::where("dokter_id", $dokter->id)->first();


    return Inertia::render('Dokter/PasienPage', [
        'poli' => $poli,
        'dokter' => $dokter,
        'data' => $rekammedikService->getPasienByDokter($dokter->id)
    ]);
})->name('dokter.pasien');

Route::get('/dokter/pasien/{id}', function (
    RekamMedikService $rekammedikService,
    $pasienId
) {
    $user = Auth::user();

    // Ambil data dokter yang sedang login
    $dokter = Dokter::where('user_id', $user->id)->first();

    if (!$dokter) {
        abort(403, 'Anda tidak diizinkan untuk mengakses halaman ini.');
    }

    // Ambil data poli yang terhubung dengan dokter
    $poli = Poli::where('dokter_id', $dokter->id)->first();

    // Ambil data rekam medis berdasarkan pasien_id dan dokter_id
    $rekamMedik = $rekammedikService->getByDetailPasien($pasienId, $dokter->id);
    // dd($rekamMedik);
    // Validasi apakah data rekam medis terkait dengan poli dan pasien yang benar
    if ($rekamMedik->isEmpty()) {
        abort(403, 'Anda tidak diizinkan untuk melihat data ini.');
    }

    return Inertia::render('Dokter/DetailPasienPage', [
        "polis" => $poli,
        "dokter" => $dokter,
        "rekammediks" => $rekamMedik,
    ]);
})->name('dokter.pasien');
