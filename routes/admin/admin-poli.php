<?php

use App\Http\Requests\PoliRequest;
use App\services\DokterService;
use App\services\PegawaiService;
use App\services\PoliService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/admin/poli', function (PoliService $poliService) {
    return Inertia::render('Admin/PoliPage', ['data' => $poliService->all(10)]);
})->name('admin.poli');

Route::get('/admin/poli/add', function (DokterService $dokterService, PegawaiService $pegawaiService, PoliService $poliService) {
    return Inertia::render('Admin/AddPoliPage', [
        'dokters' => $dokterService->data(),
        'pegawais' => $pegawaiService->data(),
        'poli' => $poliService->data(),
        'id' => null,
    ]);
})->name('admin.poli.add');


Route::get('/admin/poli/add/{id}', function (DokterService $dokterService, PoliService $poliService, PegawaiService $pegawaiService, $id) {
    return Inertia::render('Admin/AddPoliPage', [
        "dokters" => $dokterService->data(),
        "pegawais" => $pegawaiService->data(),
        "poli" => $poliService->getById($id),
        "id" => $id,
    ]);
})->name('admin.poli.add');

Route::post('/admin/poli', function (PoliRequest $poliRequest, PoliService $poliService) {
    try {
        $result = $poliService->post($poliRequest);
        if ($result) {
            return Redirect::back()->with('success');
        }
    } catch (\Throwable $th) {
        Log::error($th->getMessage());
        return Redirect::back()->withErrors(['message' => "Data Tidak Berhasil Disimpan !"]);
    }
})->name('admin.poli.post');

Route::put('/admin/poli/{id}', function (PoliRequest $PoliRequest, PoliService $poliService, $id) {
    try {
        $result = $poliService->put($PoliRequest, $id);
        if ($result) {
            return Redirect::back()->with('success');
        }
    } catch (\Throwable $th) {
        Log::error($th->getMessage());
        return Redirect::back()->withErrors(['message' => "Data Tidak Berhasil Diubah !"]);
    }
})->name('admin.poli.put');

Route::delete('/admin/poli/{id}', function (PoliService $poliService, $id) {
    try {
        $result = $poliService->delete($id);
        if ($result) {
            return Redirect::back()->with('success');
        }
    } catch (\Throwable $th) {
        Log::error($th->getMessage());
        return Redirect::back()->withErrors(['message' => "Data Tidak Berhasil Dihapus !"]);
    }
})->name('admin.poli.delete');
