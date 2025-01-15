<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ObatController;
use App\Http\Controllers\ProfileController;
use App\Http\Requests\RekamMedikRequest;
use App\Models\Dokter;
use App\services\DokterService;
use App\services\ObatService;
use App\services\RekamMedikService;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/dokter/rekammedik', function (RekamMedikService $rekammedikService, DokterService $dokterService) {
    $user = Auth::user();
    $dokter = Dokter::where("user_id", $user->id)->first();
    return Inertia::render('Dokter/RekamMedikPage', [
        'poli' => $dokterService->getPoli(),
        'data' => $rekammedikService->getByDokterId($dokter->id)
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


Route::put('/dokter/rekammedik/{id}', function (RekamMedikRequest $rekamMedikRequest, RekamMedikService $rekamMedikService, $id) {
    try {
        $rekamMedikRequest['status'] = 'dokter';
        $result = $rekamMedikService->put($rekamMedikRequest, $id);
        if ($result) {
            return Redirect::back()->with('success');
        }
    } catch (\Throwable $th) {
        return Redirect::back()->withErrors(["msg" => "Data Tidak Berhasil Disimpan/ Diubah ! "]);
    }
})->name('dokter.rekammedik.put');
