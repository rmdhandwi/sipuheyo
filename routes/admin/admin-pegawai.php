<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ObatController;
use App\Http\Controllers\ProfileController;
use App\Http\Requests\PegawaiRequest;
use App\services\PegawaiService;
use App\services\PoliService;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/admin/pegawai', function (PegawaiService $pegawaiService) {
    return Inertia::render('Admin/PegawaiPage', ['data' => $pegawaiService->all()]);
})->name('admin.pegawai');

Route::get('/admin/pegawai/add', function (PegawaiService $pegawaiService) {
    return Inertia::render('Admin/AddPegawaiPage');
})->name('admin.pegawai.add');


Route::get('/admin/pegawai/add/{id}', function (PegawaiService $pegawaiService, $id) {
    return Inertia::render('Admin/AddPegawaiPage', ["pegawai" => $pegawaiService->getById($id)]);
})->name('admin.pegawai.edit');

Route::post('/admin/pegawai', function (PegawaiRequest $PegawaiRequest, PegawaiService $pegawaiService) {
    try {
        $result = $pegawaiService->post($PegawaiRequest);
        if ($result) {
            return Redirect::back()->with('success');
        }
    } catch (\Throwable $th) {
        return Redirect::back()->withErrors($th->getMessage());
    }
})->name('admin.pegawai.post');

Route::put('/admin/pegawai/{id}', function (PegawaiRequest $PegawaiRequest, PegawaiService $pegawaiService, $id) {
    try {
        $result = $pegawaiService->put($PegawaiRequest, $id);
        if ($result) {
            return Redirect::back()->with('success');
        }
    } catch (\Throwable $th) {
        return Redirect::back()->withErrors($th->getMessage());
    }
})->name('admin.pegawai.put');

Route::delete('/admin/pegawai/{id}', function (PegawaiService $pegawaiService, $id) {
    try {
        $result = $pegawaiService->delete($id);
        if ($result) {
            return Redirect::back()->with('success');
        }
    } catch (\Throwable $th) {
        return Redirect::back()->withErrors($th->getMessage());
    }
})->name('admin.pegawai.delete');
