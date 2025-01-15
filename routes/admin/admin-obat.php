<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ObatController;
use App\Http\Controllers\ProfileController;
use App\Http\Requests\ObatRequest;
use App\services\ObatService;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/admin/obat', function (ObatService $obatService) {
    return Inertia::render('Admin/ObatPage', 
    ['data' => $obatService->all()]);
})->name('admin.obat');

Route::get('/admin/obat/add', function (ObatService $obatService) {
    return Inertia::render('Admin/AddObatPage');
})->name('admin.obat.add');


Route::get('/admin/obat/add/{id}', function (ObatService $obatService, $id) {
    return Inertia::render('Admin/AddObatPage', [
        "obat" => $obatService->getById($id)
    ]);
})->name('admin.obat.add');

Route::post('/admin/obat', function (ObatRequest $obatRequest, ObatService $obatService) {
    try {
        $result = $obatService->post($obatRequest);
        if ($result) {
            return Redirect::back()->with('success');
        }
    } catch (\Throwable $th) {
        Log::error($th->getMessage());
        return Redirect::back()->withErrors(['message' => "Data Tidak Berhasil Ditambah !"]);
    }
})->name('admin.obat.post');

Route::put('/admin/obat/{id}', function (ObatRequest $obatRequest, ObatService $obatService, $id) {
    try {
        $result = $obatService->put($obatRequest, $id);
        if ($result) {
            return Redirect::back()->with('success');
        }
    } catch (\Throwable $th) {
        Log::error($th->getMessage());
        return Redirect::back()->withErrors(['message' => "Data Tidak Berhasil Diubah !"]);
    }
})->name('admin.obat.put');

Route::delete('/admin/obat/{id}', function (ObatService $obatService, $id) {
    try {
        $result = $obatService->delete($id);
        if ($result) {
            return Redirect::back()->with('success');
        }
    } catch (\Throwable $th) {
        Log::error($th->getMessage());
        return Redirect::back()->withErrors(['message' => "Data Tidak Berhasil Dihapus !"]);
    }
})->name('admin.obat.delete');
