<?php

use Inertia\Inertia;
use App\Models\Pasien;
use App\services\ObatService;
use App\services\PoliService;
use App\services\DokterService;
use App\services\PasienService;
use App\services\RekamMedikService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Requests\RekamMedikRequest;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\DokterController;
use App\Http\Controllers\PasienController;

Route::group(['middleware' => 'role:pasien'], function () {
    Route::get('/pasien', [PasienController::class, 'index'])->name('pasien.index');
    Route::get('/pasien/rekammedik/add', [PasienController::class, 'daftar'])->name('pasien.rekammedik.add');
    Route::get('/pasien/rekammedik/{id}', function (
        ObatService $obatService,
        PoliService $poliService,
        RekamMedikService $rekammedikService,
        DokterService  $dokterService,
        $id
    ) {

        $rm = $rekammedikService->getById($id);
        $userid = Auth::user()->id;
        $pasien = Pasien::where('user_id', $userid)->first();
        return Inertia::render(
            'Pasien/AddRekamMedikPage',
            [
                'polis' => $poliService->all(),
                'pasien' => $pasien,
                'dokters' => $dokterService->all(),
                'obats' => $obatService->all(),
                "rekammedik" => $rekammedikService->getById($id),
            ],
        );
    })->name('pasien.rekammedik.detail');


    Route::put('/pasien/rekammedik/{id}', function (RekamMedikRequest $rekamMedikRequest, RekamMedikService $rekamMedikService, $id) {
        try {
            $result = $rekamMedikService->put($rekamMedikRequest, $id);
            if ($result) {
                return Redirect::back()->with('success');
            }
        } catch (\Throwable $th) {
            return Redirect::back()->withErrors(["msg" => "Data Tidak Berhasil Disimpan/ Diubah ! "]);
        }
    })->name('pasien.rekammedik.put');


    Route::post('/pasien/rekammedik', function (RekamMedikRequest $rekamMedikRequest, RekamMedikService $rekamMedikService) {
        try {
            $result = $rekamMedikService->post($rekamMedikRequest);
            if ($result) {
                return Redirect::route('pasien.index', $result->id);
            }
        } catch (\Throwable $th) {
            return Redirect::back()->withErrors("error", $th->getMessage());
        }
    })->name('pasien.rekammedik.post');
    


    Route::delete('/pasien/rekammedik/{id}', function (RekamMedikService $rekamMedikService, $id) {
        try {
            $result = $rekamMedikService->delete($id);
            if ($result) {
                return Redirect::back()->with('success');
            }
        } catch (\Throwable $th) {
            return Redirect::back()->withErrors($th->getMessage());
        }
    })->name('pasien.rekammedik.delete');
});
