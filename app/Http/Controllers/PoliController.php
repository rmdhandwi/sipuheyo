<?php

namespace App\Http\Controllers;

use App\Models\Poli;
use Inertia\Inertia;
use App\Models\Pasien;
use App\Models\Pegawai;
use App\services\PoliService;
use App\services\DokterService;
use Illuminate\Support\Facades\DB;
use App\services\RekamMedikService;
use Illuminate\Support\Facades\Auth;

class PoliController extends Controller
{

    public function index()
    {

        $user  =  Auth::user();
        $pegawai = Pegawai::where("user_id", $user->id)->first();
        $poli = Poli::where("pegawai_id", $pegawai->id)->first();

        $rmPasien = DB::table('rekam_mediks')
            ->where('poli_id', $poli->id)
            ->groupBy('pasien_id')
            ->count();

        $rmCount = DB::table('rekam_mediks')
            ->where('poli_id', $poli->id)
            ->count();

        return Inertia::render(
            "Poli/Index",
            [
                'pegawai' => $pegawai,
                'poli' => $poli,
                'resep' => $rmCount,
                'pasienCount' => $rmPasien,
                'rekammedikCount' => $rmCount,
            ]
        );
    }


    public function rekammedik(RekamMedikService $rekamMedikService)
    {
        $userid = Auth::user()->id;
        $pegawai = Pegawai::where('user_id', $userid)->first();
        $poli = Poli::where('pegawai_id', $pegawai->id)->first();
        $rekammedik = $rekamMedikService->getByPoli($poli->id);

       
        return Inertia::render('Poli/RekamMedik', [
            'pegawai' => $pegawai,
            'poli' => $poli,
            'data' => $rekammedik,
        ]);
    }

    public function daftar(PoliService $poliService, DokterService  $dokterService)
    {
        $userid = Auth::user()->id;
        $pasien = Pasien::where('user_id', $userid)->first();
        return Inertia::render('Pasien/AddRekamMedikPage', ['polis' => $poliService->all(),   'dokters' => $dokterService->all(),  'pasien' => $pasien]);
    }
}
