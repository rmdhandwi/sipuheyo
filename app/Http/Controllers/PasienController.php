<?php

namespace App\Http\Controllers;

use App\Models\Pasien;
use App\Models\RekamMedik;
use App\services\DokterService;
use App\services\PasienService;
use App\services\PoliService;
use App\services\RekamMedikService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class PasienController extends Controller
{
    public function index(RekamMedikService $rekamMedikService)
    {
        $userid = Auth::user()->id;
        $pasien = Pasien::where('user_id', $userid)->first();

        return  Inertia::render(
            "Pasien/Index",
            [
                'pasien' => $pasien,
                'data' =>  $rekamMedikService->getByPasienId($pasien->id, 10),
            ]
        );
    }

    public function daftar(PoliService $poliService, DokterService  $dokterService)
    {
        $userid = Auth::user()->id;
        $pasien = Pasien::where('user_id', $userid)->first();
        return Inertia::render('Pasien/AddRekamMedikPage', [
            'polis' => $poliService->data(),
            'dokters' => $dokterService->data(),
            'pasien' => $pasien
        ]);
    }
}
