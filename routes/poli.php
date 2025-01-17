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
use App\Models\RekamMedik;
use Illuminate\Support\Facades\Storage;

Route::group(['middleware' => 'role:pegawai'], function () {
    Route::get('/poli', [PoliController::class, 'index'])->name('poli.index');
    Route::get('/poli/rekammedik', [PoliController::class, 'rekammedik'])->name('poli.rekammedik');
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
        return Inertia::render(
            'Poli/AddRekamMedikPage',
            [
                'polis' => $poliService->data(),
                'pasien' => $pasien,
                'dokters' => $dokterService->data(),
                'obats' => $obatService->data(),
                "rekammedik" => $rekammedikService->getById($id),
            ],
        );
    })->name('poli.rekammedik.detail');


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


    // Route::get('/{filename}', function ($filename) {
    //     $filePath = "$filename";

    //     if (Storage::exists($filePath)) {
    //         return response()->file(storage_path("$filePath"));
    //     }

    //     abort(404, 'File tidak ditemukan');
    // });

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
});
