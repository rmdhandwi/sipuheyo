<?php

use App\Http\Requests\DokterRequest;
use App\services\DokterService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/admin/dokter', function (DokterService $dokterService) {
    return Inertia::render('Admin/DokterPage', ['data' => $dokterService->all()]);
})->name('admin.dokter');

Route::get('/admin/dokter/add', function (DokterService $dokterService) {
    return Inertia::render('Admin/AddDokterPage');
})->name('admin.dokter.add');


Route::get('/admin/dokter/add/{id}', function (DokterService $dokterService, $id) {
    return Inertia::render('Admin/AddDokterPage', ["dokter" => $dokterService->getById($id)]);
})->name('admin.dokter.add');

Route::post('/admin/dokter', function (DokterRequest $DokterRequest, DokterService $dokterService) {
    try {
        $result = $dokterService->post($DokterRequest);
        if ($result) {
            return Redirect::back()->with('success');
        }
    } catch (\Throwable $th) {
        Log::error($th->getMessage());
        return Redirect::back()->withErrors(['message' => "Data Tidak Berhasil Disimpan !"]);
    }
})->name('admin.dokter.post');

Route::put('/admin/dokter/{id}', function (DokterRequest $DokterRequest, DokterService $dokterService, $id) {
    try {
        $result = $dokterService->put($DokterRequest, $id);
        if ($result) {
            return Redirect::back()->with('success');
        }
    } catch (\Throwable $th) {
        Log::error($th->getMessage());
        return Redirect::back()->withErrors(['message' => "Data Tidak Berhasil Diubah !"]);
    }
})->name('admin.dokter.put');

Route::delete('/admin/dokter/{id}', function (DokterService $dokterService, $id) {
    try {
        $result = $dokterService->delete($id);
        if ($result) {
            return Redirect::back()->with('success');
        }
    } catch (\Throwable $th) {
        Log::error($th->getMessage());
        return Redirect::back()->withErrors(['message' => "Data Tidak Berhasil Dihapus !"]);
    }
})->name('admin.dokter.delete');
