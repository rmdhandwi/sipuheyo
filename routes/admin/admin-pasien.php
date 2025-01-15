<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ObatController;
use App\Http\Controllers\ProfileController;
use App\Http\Requests\PasienRequest;
use App\services\PasienService;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/admin/pasien', function (PasienService $pasienService) {
    return Inertia::render('Admin/PasienPage', ['data' => $pasienService->all()]);
})->name('admin.pasien');

Route::get('/admin/pasien/add', function () {
    return Inertia::render('Admin/AddPasienPage');
})->name('admin.pasien.add');


Route::get('/admin/pasien/add/{id}', function (PasienService $pasienService, $id) {
    return Inertia::render('Admin/AddPasienPage', [
        "pasien" => $pasienService->getById($id)
    ]);
})->name('admin.pasien.add');

Route::post('/admin/pasien', function (PasienRequest $pasienRequest, PasienService $pasienService) {
    try {
        $result = $pasienService->post($pasienRequest);
        if ($result) {
            return Redirect::back()->with('success');
        }
    } catch (\Throwable $th) {
        return Redirect::back()->withErrors($th->getMessage());
    }
})->name('admin.pasien.post');

Route::put('/admin/pasien/{id}', function (PasienRequest $pasienRequest, PasienService $pasienService, $id) {
    try {
        $result = $pasienService->put($pasienRequest, $id);
        if ($result) {
            return Redirect::back()->with('success');
        }
    } catch (\Throwable $th) {
        return Redirect::back()->withErrors($th->getMessage());
    }
})->name('admin.pasien.put');

Route::delete('/admin/pasien/{id}', function (PasienService $pasienService, $id) {
    try {
        $result = $pasienService->delete($id);
        if ($result) {
            return Redirect::back()->with('success');
        }
    } catch (\Throwable $th) {
        return Redirect::back()->withErrors($th->getMessage());
    }
})->name('admin.pasien.delete');
