<?php

use App\Http\Requests\PasienRequest;
use App\Models\Pasien;
use App\services\PasienService;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/admin/pasien', function (PasienService $pasienService) {
    return Inertia::render('Admin/PasienPage', ['data' => $pasienService->all()]);
})->name('admin.pasien');

Route::get('/admin/pasien/add', function () {
    // Hitung jumlah pasien yang ada
    $jumlahPasien = Pasien::count();

    // Buat kode RM berdasarkan jumlah pasien + 1
    $kodeRM = 'RM' . str_pad($jumlahPasien + 1, 8, '0', STR_PAD_LEFT);

    return Inertia::render('Admin/AddPasienPage', ['kode' => $kodeRM]);
})->name('admin.pasien.add');


Route::get('/admin/pasien/add/{id}', function (PasienService $pasienService, $id) {
    // Hitung jumlah pasien yang ada
    $jumlahPasien = Pasien::count();

    // Buat kode RM berdasarkan jumlah pasien + 1
    $kodeRM = 'RM' . str_pad($jumlahPasien + 1, 8, '0', STR_PAD_LEFT);

    return Inertia::render('Admin/AddPasienPage', [
        "pasien" => $pasienService->getById($id),
        "kode" => $kodeRM
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
