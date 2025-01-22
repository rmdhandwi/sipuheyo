<?php
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;


Route::group(['middleware' => 'role:admin'], function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
    Route::get('/admin/jadwalberobat', [AdminController::class, 'jadwalberobat'])->name('admin.jadwalberobat');
    Route::get('/admin/jadwalberobat/{date}', [AdminController::class, 'jadwalberobatByDate'])->name('admin.jadwalberobat');
    include 'admin-obat.php';
    include 'admin-poli.php';
    include 'admin-dokter.php';
    include 'admin-pegawai.php';
    include 'admin-pasien.php';
    include 'admin-rekammedik.php';
});
