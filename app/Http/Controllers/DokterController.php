<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Obat;
use App\Models\Poli;
use Inertia\Inertia;
use App\Models\Dokter;
use App\Models\Pasien;
use App\Models\Pegawai;
use App\Models\RekamMedik;
use App\services\DokterService;
use Illuminate\Support\Facades\DB;
use App\services\RekamMedikService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class DokterController extends Controller
{
    public function index(DokterService $dokterService)
    {
        $poli = $dokterService->getPoli();

        $rmPasien = DB::table('rekam_mediks')
        ->groupBy('pasien_id')
        ->count();


        $rmCount = DB::table('rekam_mediks')
        ->where('poli_id',$poli->id)
        ->count();

        return Inertia::render(
            "Dokter/Index",
            [
                'resep' => $rmCount,
                'pasien' => $rmPasien,
                'rekammedik' =>$rmCount,
            ]
        );
    }
    public function jadwalberobat()
    {
        $user = Auth::user();
            $dokter = Dokter::where('user_id',$user->id)->firstOrFail();
        return Inertia::render(
            "Dokter/JadwalBerobatPage",['dokter' => $dokter]
        );
    }

    public function jadwalberobatByDate(RekamMedikService $rekamMedikService,$dokterId, $date)
    {
        try {
          
            $results = RekamMedik::where('dokter_id', $dokterId)
                ->whereDate('konsultasi_berikut', '=', $date)
                ->get();
            foreach ($results as $key => $result) {
                $result->poli;
                $result->dokter;
                $result->pasien;
            }
            return $results->toJson();
        } catch (\Throwable $th) {
           return Redirect()->back()->withErrors(["message"=>$th->getMessage()]);
        }
    }
}
