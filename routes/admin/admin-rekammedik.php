<?php

use App\Http\Requests\RekamMedikRequest;
use App\services\DokterService;
use App\services\ObatService;
use App\services\PasienService;
use App\services\PoliService;
use App\services\RekamMedikService;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/admin/rekammedik', function (RekamMedikService $rekamMedikService, PoliService $poliService) {
    return Inertia::render('Admin/RekamMedikPage', [
        'data' => $rekamMedikService->all(10),
        'polis' => $poliService->data(),
    ]);
})->name('admin.rekammedik');

Route::get('/admin/rekammedik/add', function (PoliService $poliService, DokterService $dokterService, PasienService $pasienService) {
    return Inertia::render('Admin/AddRekamMedikPage', [
        'polis' => $poliService->data(),
        'dokters' => $dokterService->data(),
        'pasiens' => $pasienService->data()
    ]);
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
            "dokters" => $dokterService->data(),
            "pasiens" => $pasienService->data(),
            "polis" => $poliService->data(),
            "obats" => $obatService->data(),
            "rekammedik" => $rekamMedikService->getById($id)
        ]
    );
})->name('admin.rekammedik.add');

Route::post('/admin/rekammedik', function (RekamMedikRequest $rekamMedikRequest, RekamMedikService $rekamMedikService) {
    try {
        $result = $rekamMedikService->antrian($rekamMedikRequest);
        if ($result) {
            return redirect()->route('admin.rekammedik')->with('success', 'Pendaftaran berhasil!');
        }
    } catch (\Throwable $th) {
        return back()->withErrors(['error' => $th->getMessage()]);
    }
})->name('admin.rekammedik.post');

Route::get('/admin/rekammedik/detail/{id}', function (
    RekamMedikService $rekamMedikService,
    ObatService $obatService,
    $id

) {
    return Inertia::render(
        'Admin/DetailRekamMedik',
        [
            "obats" => $obatService->data(),
            "rekammedik" => $rekamMedikService->getByDetailId($id)
        ]
    );
})->name('admin.rekammedik.detail');

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
