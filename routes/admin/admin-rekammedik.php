<?php

use App\Http\Requests\RekamMedikRequest;
use App\Models\Dokter;
use App\Models\Pasien;
use App\Models\Poli;
use App\Models\RekamMedik;
use App\services\DokterService;
use App\services\ObatService;
use App\services\PasienService;
use App\services\PoliService;
use App\services\RekamMedikService;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Pagination\LengthAwarePaginator;



Route::get('/admin/rekammedik', function () {
    // Mengambil data pasien yang memiliki rekam medik dengan grup berdasarkan pasien_id
    $rekammedik = RekamMedik::with('pasien')
        ->selectRaw('
            pasien_id, 
            COUNT(*) as total_rekam_medik, 
            SUM(CASE WHEN status = "Baru" THEN 1 ELSE 0 END) as total_status_baru,
            SUM(CASE WHEN status = "Poli" THEN 1 ELSE 0 END) as total_status_poli,
            (SUM(CASE WHEN status = "Baru" THEN 1 ELSE 0 END) + SUM(CASE WHEN status = "Poli" THEN 1 ELSE 0 END)) as total_status_sum,
            MAX(tanggal) as tanggal
        ')
        ->groupBy('pasien_id')
        ->orderBy('tanggal', 'DESC')
        ->paginate(10);

    // Ambil data poli
    $polis = Poli::all();

    // Menampilkan data melalui Inertia
    return Inertia::render('Admin/RekamMedikPage', [
        'data' => $rekammedik,
        'polis' => $polis,
    ]);
})->name('admin.rekammedik');



Route::get('/admin/rekammedik/pasien/{id}', function ($id) {
    // Mengambil rekam medis berdasarkan pasien_id yang diberikan
    $rekammedik = RekamMedik::with(['dokter', 'poli', 'pasien'])
        ->where('pasien_id', $id)
        ->orderByRaw("FIELD(status, 'Baru', 'Poli','Dokter')") // Status "baru" lebih dulu
        ->orderBy('tanggal', 'DESC') // Jika status sama, urutkan berdasarkan tanggal terbaru
        ->paginate(10); // Paginasi dengan 10 data per halaman

    // Ambil semua data poli
    $polis = Poli::all();
    $dokters = Dokter::all();

    // Mengambil informasi pasien
    $pasien = Pasien::find($id);

    // Menampilkan data melalui Inertia
    return Inertia::render('Admin/RekamMedikPasien', [
        'data' => $rekammedik,
        'polis' => $polis,
        'dokters' => $dokters,
        'kode' => $pasien, // Mengirimkan data pasien ke frontend
    ]);
})->name('admin.rekammedik.pasien');



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
