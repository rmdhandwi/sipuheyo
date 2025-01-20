<?php

use App\Http\Requests\DokterRekamMedikRequest;
use App\Models\Dokter;
use App\Models\Poli;
use App\services\DokterService;
use App\services\ObatService;
use App\services\RekamMedikService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/dokter/rekammedik', function (RekamMedikService $rekammedikService) {
    $user = Auth::user();
    $dokter = Dokter::where("user_id", $user->id)->first();
    $poli = Poli::where("dokter_id", $dokter->id)->first();

    return Inertia::render('Dokter/RekamMedikPage', [
        'poli' => $poli,
        'dokter' => $dokter,
        'data' => $rekammedikService->getByDokterId($dokter->id, 10)
    ]);
})->name('dokter.rekammedik');


Route::get('/dokter/rekammedik/{id}', function (
    ObatService $obatService,
    DokterService $dokterService,
    RekamMedikService $rekammedikService,
    $id
) {
    return Inertia::render(
        'Dokter/RekamMedikPasienPage',
        [
            'poli' => $dokterService->getPoli(),
            "obats" => $obatService->data(),
            "rekammedik" => $rekammedikService->getById($id),
        ],
    );
})->name('dokter.pasien.rekammedik');

Route::get('/dokter/rekammedik/detail/{id}', function (
    RekamMedikService $rekamMedikService,
    $id

) {
    $user = Auth::user();
    $dokter = Dokter::where("user_id", $user->id)->first();
    $poli = Poli::where("dokter_id", $dokter->id)->first();

    // dd($poli);
    return Inertia::render(
        'Dokter/DetailRekamMedik',
        [
            "poli" => $poli,
            "rekammedik" => $rekamMedikService->getByDetailId($id)
        ]
    );
})->name('dokter.rekammedik.detail');

Route::put('/dokter/rekammedik/{id}', function (DokterRekamMedikRequest $DokterRekamMedikRequest, RekamMedikService $rekamMedikService, $id) {
    try {
        $result = $rekamMedikService->dokterput($DokterRekamMedikRequest, $id);
        if ($result) {
            return Redirect::back()->with('success');
        }
    } catch (\Throwable $th) {
        return Redirect::back()->withErrors(["msg" => "Data Tidak Berhasil Disimpan/ Diubah ! "]);
    }
})->name('dokter.rekammedik.put');
