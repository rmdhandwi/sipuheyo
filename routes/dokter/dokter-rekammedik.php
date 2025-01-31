<?php

use App\Http\Requests\DokterRekamMedikRequest;
use App\Models\Dokter;
use App\Models\Poli;
use App\Models\RekamMedik;
use App\services\DokterService;
use App\services\ObatService;
use App\services\RekamMedikService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Pagination\LengthAwarePaginator;

Route::get('/dokter/rekammedik/pasien/{id}', function ($id) {
    $user = Auth::id();

    // Mencari dokter berdasarkan user_id
    $dokter = Dokter::where("user_id", $user)->first();
    if (!$dokter) {
        abort(403, 'Dokter Tidak Ditemukan');
    }

    // Mencari poli berdasarkan dokter_id
    $poli = Poli::where('dokter_id', $dokter->id)->first();
    if (!$poli) {
        abort(403, 'Poli Tidak Ditemukan');
    }

    // Mendapatkan awalan kode dari poli
    $kodeAwal = substr($poli->kode, 0, 2);

    // Mengambil data rekam medis berdasarkan pasien_id yang dipassing
    $rekammedik = RekamMedik::with(['dokter', 'poli', 'pasien'])
        ->where('pasien_id', $id) // **Filter berdasarkan pasien yang dipassing**
        ->whereHas('poli', function ($query) use ($dokter, $kodeAwal) {
            $query->where('dokter_id', $dokter->id)
                ->where('kode', 'LIKE', $kodeAwal . '%')
                ->whereIn('status', ['poli', 'dokter']);
        })
        ->orderBy('tanggal', 'DESC')
        ->paginate(10); // **Pagination tetap digunakan**

    $kode = RekamMedik::where('pasien_id', $id)->first();

    // Kirim data ke Inertia
    return Inertia::render('Dokter/PasienRekamMedik', [
        'dokter' => $dokter,
        'poli' => $poli,
        'data' => $rekammedik,
        'kode' => $kode,
    ]);
})->name('dokter.rekammedik');


Route::get('/dokter/rekammedik', function () {
    $user = Auth::id();

    // Mencari dokter berdasarkan user_id
    $dokter = Dokter::where("user_id", $user)->first();
    if (!$dokter) {
        abort(403, 'Dokter Tidak Ditemukan');
    }

    // Mencari poli berdasarkan dokter_id
    $poli = Poli::where('dokter_id', $dokter->id)->first();
    if (!$poli) {
        abort(403, 'Poli Tidak Ditemukan');
    }

    // Mendapatkan awalan kode dari poli
    $kodeAwal = substr($poli->kode, 0, 2);

    // Ambil data rekam medis berdasarkan dokter dan status
    $rekammedikQuery = RekamMedik::with(['dokter', 'poli', 'pasien'])
        ->whereHas('poli', function ($query) use ($dokter, $kodeAwal) {
            $query->where('dokter_id', $dokter->id)
                ->where('kode', 'LIKE', $kodeAwal . '%')
                ->whereIn('status', ['poli', 'dokter']);
        })
        ->get()
        ->groupBy('kode'); // **Mengelompokkan berdasarkan pasien_id**

    // **Pagination Manual**
    $perPage = 10; // **Jumlah data per halaman**
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

    // **Kirim ke Inertia**
    return Inertia::render('Dokter/RekamMedikPage', [
        'dokter' => $dokter,
        'poli' => $poli,
        'data' => $rekammedikPaginated,
    ]);
})->name('dokter.rekammedik');



Route::get('/dokter/rekammedik/{id}', function (
    ObatService $obatService,
    DokterService $dokterService,
    RekamMedikService $rekammedikService,
    $id
) {
    return Inertia::render(
        'Dokter/RekamMedikPasienPage',
        [
            'poli' => $dokterService->getPoli(),
            "obats" => $obatService->data(),
            "rekammedik" => $rekammedikService->getById($id),
        ],
    );
})->name('dokter.pasien.rekammedik');


Route::get('/dokter/rekammedik/detail/{id}', function (
    RekamMedikService $rekamMedikService,
    $id

) {
    $user = Auth::user();
    $dokter = Dokter::where("user_id", $user->id)->first();
    $poli = Poli::where("dokter_id", $dokter->id)->first();

    // dd($poli);
    return Inertia::render(
        'Dokter/DetailRekamMedik',
        [
            "poli" => $poli,
            "rekammedik" => $rekamMedikService->getByDetailId($id)
        ]
    );
})->name('dokter.rekammedik.detail');

Route::put('/dokter/rekammedik/{id}', function (DokterRekamMedikRequest $DokterRekamMedikRequest, RekamMedikService $rekamMedikService, $id) {
    try {
        $result = $rekamMedikService->dokterput($DokterRekamMedikRequest, $id);
        if ($result) {
            return Redirect::back()->with('success');
        }
    } catch (\Throwable $th) {
        return Redirect::back()->withErrors(["msg" => "Data Tidak Berhasil Disimpan/ Diubah ! "]);
    }
})->name('dokter.rekammedik.put');
