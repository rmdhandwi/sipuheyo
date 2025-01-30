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


Route::get('/admin/rekammedik', function () {
    // Mengambil semua rekam medis dengan relasi dokter, poli, dan pasien
    $rekammedikQuery = RekamMedik::with(['dokter', 'poli', 'pasien'])
        ->orderBy('tanggal', 'DESC')
        ->get()
        ->groupBy('pasien_id'); // Mengelompokkan berdasarkan pasien_id

    // Tambahkan kode_rm dengan urutan tanpa grup pasien
    $rekammedikArray = $rekammedikQuery->values()->map(function ($item, $index) {
        return [
            'kode_rm' => 'RM' . str_pad($index + 1, 6, '0', STR_PAD_LEFT), // RM000001, RM000002, dst.
            'rekam_medik' => $item,
        ];
    })->toArray();

    // **Pagination Manual**
    $perPage = 10; // Jumlah data per halaman
    $currentPage = request()->get('page', 1);
    $currentItems = array_slice($rekammedikArray, ($currentPage - 1) * $perPage, $perPage);
    $rekammedikPaginated = new \Illuminate\Pagination\LengthAwarePaginator(
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

    // Ambil data poli untuk tampilan
    $polis = Poli::all();

    // Menampilkan data melalui Inertia
    return Inertia::render('Admin/RekamMedikPasien', [
        'data' => $rekammedik,
        'polis' => $polis,
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
