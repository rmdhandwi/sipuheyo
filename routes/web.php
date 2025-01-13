<?php

use App\Models\User;
use Inertia\Inertia;
use App\services\ObatService;
use App\Http\Requests\ObatRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\ObatController;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {

    if (Auth::check()) {
        switch (Auth::user()->role) {
            case 'admin':
                return redirect()->intended(route('admin.index', absolute: false));
            case 'dokter':
                return redirect()->intended(route('dokter.index', absolute: false));
            case 'pegawai':
                return redirect()->intended(route('poli.index', absolute: false));
            case 'pasien':
                return redirect()->intended(route('pasien.index', absolute: false));
            default:
                return redirect()->intended(route('admin.index', absolute: false));
        }
    } else {
        return Redirect::route('login');
    }
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    //ADMIN
    include 'pasien.php';
    include 'poli.php';
    include 'admin/admin.php';
    include 'dokter/dokter.php';
});
require __DIR__ . '/auth.php';
