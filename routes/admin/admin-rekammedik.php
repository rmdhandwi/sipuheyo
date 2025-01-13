<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\PoliController;
use App\Http\Controllers\ProfileController;
use App\Http\Requests\RekamMedikRequest;
use App\services\DokterService;
use App\services\ObatService;
use App\services\PasienService;
use App\services\PoliService;
use App\services\RekamMedikService;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/admin/rekammedik', function (RekamMedikService $rekamMedikService, PoliService $poliService) {
    return Inertia::render('Admin/RekamMedikPage', [ 'data' => $rekamMedikService->all(),  "polis" => $poliService->all(),]);
})->name('admin.rekammedik');

Route::get('/admin/rekammedik/add', function (PoliService $poliService, DokterService $dokterService, PasienService $pasienService) {
    return Inertia::render('Admin/AddRekamMedikPage', ['polis' => $poliService->all(), 'dokters' => $dokterService->all(), 'pasiens' => $pasienService->all()]);
})->name('admin.rekammedik.add');


Route::get('/admin/rekammedik/add/{id}', function (
    RekamMedikService $rekamMedikService,
    PoliService $poliService,
    DokterService $dokterService,
    ObatService $obatService,
    PasienService $pasienService,
    $id
) {
    return Inertia::render(
        'Admin/AddRekamMedikPage',
        [
            "dokters" => $dokterService->all(),
            "pasiens" => $pasienService->all(),
            "polis" => $poliService->all(),
            "obats" => $obatService->all(),
            "rekammedik" => $rekamMedikService->getById($id)
        ]
    );
})->name('admin.rekammedik.add');

Route::post('/admin/rekammedik', function (RekamMedikRequest $rekamMedikRequest, RekamMedikService $rekamMedikService) {
    try {
        $result = $rekamMedikService->post($rekamMedikRequest);
        if ($result) {
            return Redirect::route('admin.rekammedik.add', $result->id);
        }
    } catch (\Throwable $th) {
        return Redirect::back()->withErrors("error", $th->getMessage());
    }
})->name('admin.rekammedik.post');

Route::put('/admin/rekammedik/{id}', function (RekamMedikRequest $rekamMedikRequest, RekamMedikService $rekamMedikService, $id) {
    try {
        $result = $rekamMedikService->put($rekamMedikRequest, $id);
        if ($result) {
            return Redirect::back()->with('success');
        }
    } catch (\Throwable $th) {
        return Redirect::back()->withErrors(["msg" => "Data Tidak Berhasil Disimpan/ Diubah ! "]);
    }
})->name('admin.rekammedik.put');

Route::delete('/admin/rekammedik/{id}', function (RekamMedikService $rekamMedikService, $id) {
    try {
        $result = $rekamMedikService->delete($id);
        if ($result) {
            return Redirect::back()->with('success');
        }
    } catch (\Throwable $th) {
        return Redirect::back()->withErrors($th->getMessage());
    }
})->name('admin.rekammedik.delete');
