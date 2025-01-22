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

Route::get('/dokter/rekammedik', function (RekamMedikService $rekammedikService) {
    $user = Auth::user()->id;

    // Mencari pegawai berdasarkan user_id
    $dokter = Dokter::where("user_id", $user)->first();

    // Pastikan dokter ditemukan
    if (!$dokter) {
        abort(403, 'Dokter Tidak Ditemukan');
    }

    // Mencari poli berdasarkan dokter_id
    $poli = Poli::where('dokter_id', $dokter->id)->first();

    // Pastikan poli ditemukan
    if (!$poli) {
        abort(403, 'Poli Tidak Ditemukan');
    }

    // Mendapatkan awalan kode dari poli
    $kodeAwal = substr($poli->kode, 0, 2); // Mengambil awalan kode, misal "PA" dari "PA01"

    // Mengambil data rekam medik berdasarkan awalan kode dan dokter_id
    $rekammedik = RekamMedik::with(['dokter', 'poli', 'pasien'])
        ->whereHas('poli', function ($query) use ($dokter, $kodeAwal) {
            // Filter poli berdasarkan awalan kode dan pegawai_id
            $query->where('dokter_id', $dokter->id)
                ->where('kode', 'LIKE', $kodeAwal . '%') // Awalan kode diikuti karakter lain
                ->where('status', '=', 'poli') // Awalan kode diikuti karakter lain
                ->orWhere('status', '=', 'dokter'); // Awalan kode diikuti karakter lain
        })
        ->orderBy('tanggal', 'DESC') // Urutkan berdasarkan tanggal
        ->paginate(10); // Paginasi, 10 data per halaman

    // Menampilkan data melalui Inertia
    return Inertia::render('Dokter/RekamMedikPage', [
        'dokter' => $dokter,
        'poli' => $poli,
        'data' => $rekammedik,
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
