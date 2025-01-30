<?php

use Inertia\Inertia;
use App\Models\Pasien;
use App\services\ObatService;
use App\services\PoliService;
use App\services\DokterService;
use App\services\RekamMedikService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\PoliController;
use App\Http\Requests\PoliRekamMedikRequest;
use App\Models\Pegawai;
use App\Models\Poli;
use App\Models\RekamMedik;
use App\services\PegawaiService;
use Illuminate\Support\Facades\Storage;

Route::group(['middleware' => 'role:pegawai'], function () {
    Route::get('/poli', [PoliController::class, 'index'])->name('poli.index');
    Route::get('/poli/rekammedik', [PoliController::class, 'rekammedik'])->name('poli.rekammedik');
    Route::get('/poli/rekammedik/pasien/{id}', [PoliController::class, 'rmPasien'])->name('poli.rmpasien');
    Route::get('/poli/rekammedik/add', [PoliController::class, 'daftar'])->name('poli.rekammedik.add');

    Route::get('/poli/rekammedik/{id}', function (
        ObatService $obatService,
        PoliService $poliService,
        RekamMedikService $rekammedikService,
        DokterService  $dokterService,
        $id
    ) {
        $rm = $rekammedikService->getById($id);
        $userid = Auth::user()->id;
        $pasien = Pasien::where('id', $rm->pasien_id)->first();
        $userid = Auth::user()->id;
        $pegawai = Pegawai::where('user_id', $userid)->first();
        $poli = Poli::where('pegawai_id', $pegawai->id)->first();
        return Inertia::render(
            'Poli/AddRekamMedikPage',
            [
                'polis' => $poliService->data(),
                'pasien' => $pasien,
                'poli' => $poli,
                'pegawai' => $pegawai,
                'dokters' => $dokterService->data(),
                'obats' => $obatService->data(),
                "rekammedik" => $rekammedikService->getById($id),
            ],
        );
    })->name('poli.rekammedik.detail');

    Route::get('/poli/rekammedik/detail/{id}', function (
        RekamMedikService $rekamMedikService,
        $id

    ) {
        $user  =  Auth::user();
        $pegawai = Pegawai::where("user_id", $user->id)->first();
        $poli = Poli::where("pegawai_id", $pegawai->id)->first();

        return Inertia::render(
            'Poli/DetailRekamMedikPage',
            [
                "poli" => $pegawai,
                "pegawai" => $poli,
                "rekammedik" => $rekamMedikService->getByDetailId($id)
            ]
        );
    })->name('poli.detail.rekammedik');


    Route::post('/poli/rekammedik/{id}', function (PoliRekamMedikRequest $rekamMedikRequest, RekamMedikService $rekamMedikService, $id) {
        try {
            // Dapatkan rekam medik yang sedang diupdate
            $rekamMedik = RekamMedik::findOrFail($id);

            // Proses upload file jika ada
            if ($rekamMedikRequest->hasFile('file')) {
                $file = $rekamMedikRequest->file('file');

                if (
                    $rekamMedik->hasil_lab &&
                    Storage::disk('public')->exists(str_replace('/storage/', '', $rekamMedik->hasil_lab))
                ) {
                    Storage::disk('public')->delete(str_replace('/storage/', '', $rekamMedik->hasil_lab));
                }

                $filename = uniqid() . '.' . $file->getClientOriginalExtension();
                $path = 'upload/' . $filename;

                // Simpan file ke storage publik
                $insertFile = Storage::disk('public')->put($path, file_get_contents($file));

                if ($insertFile) {
                    $rekamMedikRequest->merge(['hasil_lab' => Storage::url($path)]);
                } else {
                    throw new \Exception("Gagal Upload Gambar.");
                }
            }

            // Update data
            $result = $rekamMedikService->put($rekamMedikRequest, $id);

            if ($result) {
                return redirect()->route('poli.rekammedik')->with('success', 'Data berhasil disimpan.');
            }
        } catch (\Throwable $th) {
            return Redirect::back()->withErrors(["error" => $th->getMessage()]);
        }
    })->name('poli.rekammedik.put');


    Route::delete('/rekammedik/{id}', function (RekamMedikService $rekamMedikService, $id) {
        try {
            $result = $rekamMedikService->delete($id);
            if ($result) {
                return Redirect::back()->with('success');
            }
        } catch (\Throwable $th) {
            return Redirect::back()->withErrors($th->getMessage());
        }
    })->name('poli.rekammedik.delete');


    Route::get('/poli/pasien', function (RekamMedikService $rekammedikService) {
        $user = Auth::user();
        $pegawai = Pegawai::where("user_id", $user->id)->first();

        if (!$pegawai) {
            abort(403, 'Akses Ditolak, Pegawai Tidak Ditemukan.');
        }

        $poli = Poli::where("pegawai_id", $pegawai->id)->get(); // Ambil semua poli yang dikelola pegawai
        $poliname = Poli::where("pegawai_id", $pegawai->id)->first();

        if ($poli->isEmpty()) {
            abort(403, 'Akses Ditolak, Poli Tidak Ditemukan.');
        }

        // Ambil kode poli dan ekstrak kode sebelum angka (misal, PA01 -> PA)
        $kodePoli = substr($poli->first()->kode, 0, -2); // Mengambil "RK" sebelum "01"

        // Ambil data rekam medik berdasarkan poli_id dan kode poli
        $dataPasien = RekamMedik::with('pasien')
            ->whereIn('poli_id', $poli->pluck('id')) // Filter berdasarkan semua poli_id
            ->whereHas('poli', function ($query) use ($kodePoli) {
                $query->where('kode', 'like', "$kodePoli%"); // Ambil semua poli yang kode-nya diawali dengan "RK"
            })
            ->selectRaw('pasien_id, COUNT(*) as total_pemeriksaan, MAX(tanggal) as terakhir_diperiksa')
            ->groupBy('pasien_id')
            ->orderBy('terakhir_diperiksa', 'desc') // Mengurutkan berdasarkan tanggal terakhir diperiksa
            ->paginate(10); // Sesuaikan jumlah data per halaman

        return Inertia::render('Poli/PasienPage', [
            'poli' => $poliname,
            'pegawai' => $pegawai,
            'data' => $dataPasien,
        ]);
    })->name('pegawai.pasien');


    Route::get('/poli/pasien/{id}', function (
        RekamMedikService $rekammedikService,
        $pasienId
    ) {
        $user = Auth::user();
        $pegawai = Pegawai::where('user_id', $user->id)->first();

        if (!$pegawai) {
            abort(403, 'Akses Di Tolak, Pegawai Tidak Ditemukan.');
        }

        // Ambil data poli yang terhubung dengan pegawai
        $poli = Poli::where('pegawai_id', $pegawai->id)->first();

        if (!$poli) {
            abort(403, 'Akses Di Tolak, Poli Tidak Ditemukan.');
        }

        // Ambil data rekam medis berdasarkan pasien_id dan poli
        $rekamMedik = $rekammedikService->getByDetailPasien($pasienId, $poli->id);

        // Validasi apakah data rekam medis terkait dengan poli dan pasien yang benar
        if ($rekamMedik->isEmpty()) {
            abort(403, 'Anda tidak diizinkan untuk melihat data ini.');
        }

        return Inertia::render('Poli/DetailPasienPage', [
            "polis" => $poli,
            "pegawai" => $pegawai,
            "rekammediks" => $rekamMedik,
        ]);
    })->name('pegawai.pasien.detail');
});
