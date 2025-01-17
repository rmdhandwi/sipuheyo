<?php

namespace App\Http\Controllers;

use App\Models\Dokter;
use App\Models\Obat;
use App\Models\Pasien;
use App\Models\Pegawai;
use App\Models\Poli;
use App\Models\RekamMedik;
use App\services\DokterService;
use App\services\ObatService;
use App\services\PasienService;
use App\services\PegawaiService;
use App\services\PoliService;
use App\services\RekamMedikService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class AdminController extends Controller
{
    public function index()
    {

        return Inertia::render(
            "Admin/Index",
            [
                'obat' => Obat::count(),
                'pasien' => Pasien::count(),
                'dokter' => Dokter::count(),
                'poli' => Poli::count(),
                'pegawai' => Pegawai::count(),
                'rekammedik' => RekamMedik::count(),
            ]
        );
    }


    public function jadwalberobat(PoliService $poliService, DokterService $dokterService)
    {
        $rekamMedik = RekamMedik::with(['poli', 'pasien', 'dokter'])
            ->whereNotNull('konsultasi_berikut')
            ->orderBy('konsultasi_berikut', 'DESC')
            ->paginate(10);

        $poli = $poliService->data();
        $dokter = $dokterService->data();

        return Inertia::render(
            "Admin/JadwalBerobatPage",
            [
                'poli' => $poli,
                'dokter' => $dokter,
                'data' => $rekamMedik
            ]
        );
    }


    public function jadwalberobatByDate(RekamMedikService $rekamMedikService, $date)
    {
        return Redirect::back()->with('success', $rekamMedikService->all());
    }
}
