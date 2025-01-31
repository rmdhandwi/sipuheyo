<?php

use App\Http\Requests\RekamMedikRequest;
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
    // Mengambil semua rekam medis dengan relasi dokter, poli, dan pasien
    $rekammedikQuery = RekamMedik::with(['dokter', 'poli', 'pasien'])
        ->orderBy('tanggal', 'DESC')
        ->get()
        ->groupBy('pasien_id'); // Mengelompokkan berdasarkan kode

    // **Pagination Manual**
    $perPage = 10; // Jumlah data per halaman
    $currentPage = request()->get('page', 1);

    // Mengubah koleksi ke dalam bentuk array numerik agar bisa diproses dengan array_slice()
    $rekammedikArray = $rekammedikQuery->values()->toArray();

    // Menggunakan array_slice untuk paginasi manual
    $currentItems = array_slice($rekammedikArray, ($currentPage - 1) * $perPage, $perPage);

    // Membuat instance LengthAwarePaginator
    $rekammedikPaginated = new LengthAwarePaginator(
        $currentItems,
        count($rekammedikArray),
        $perPage,
        $currentPage,
        ['path' => request()->url(), 'query' => request()->query()]
    );

    // Ambil data poli
    $polis = Poli::all();

    // Menampilkan data melalui Inertia
    return Inertia::render('Admin/RekamMedikPage', [
        'data' => $rekammedikPaginated,
        'polis' => $polis,
    ]);
})->name('admin.rekammedik');



Route::get('/admin/rekammedik/pasien/{id}', function ($id) {
    // Mengambil rekam medis berdasarkan pasien_id yang diberikan
    $rekammedik = RekamMedik::with(['dokter', 'poli', 'pasien'])
        ->where('pasien_id', $id)
        ->orderBy('tanggal', 'DESC')
        ->paginate(10); // Paginasi dengan 10 data per halaman

    // Ambil semua data poli
    $polis = Poli::all();

    // Mengambil hanya satu kode dari rekam medis pasien
    $kodeRM = RekamMedik::where('pasien_id', $id)->first(); // Mengambil kode pertama yang ditemukan

    // Menampilkan data melalui Inertia
    return Inertia::render('Admin/RekamMedikPasien', [
        'data' => $rekammedik,
        'polis' => $polis,
        'kode' => $kodeRM, // Mengirimkan kode ke frontend
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
