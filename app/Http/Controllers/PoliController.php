<?php

namespace App\Http\Controllers;

use App\Models\Poli;
use Inertia\Inertia;
use App\Models\Pasien;
use App\Models\Pegawai;
use App\Models\RekamMedik;
use App\services\PoliService;
use App\services\DokterService;
use Illuminate\Support\Facades\DB;
use App\services\RekamMedikService;
use Illuminate\Support\Facades\Auth;

class PoliController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Cari pegawai berdasarkan user_id
        $pegawai = Pegawai::where("user_id", $user->id)->first();

        // Jika pegawai tidak ditemukan, logout dan lempar exception
        if (!$pegawai) {
            Auth::logout();
            abort(403, 'Akses ditolak. Data pegawai tidak ditemukan. Anda telah logout.');
        }

        // Cari poli berdasarkan pegawai_id
        $poli = Poli::where("pegawai_id", $pegawai->id)->first();

        // Jika poli tidak ditemukan, logout dan lempar exception
        if (!$poli) {
            Auth::logout();
            abort(403, 'Akses ditolak. Data poli tidak ditemukan. Anda telah logout.');
        }

        // Mendapatkan awalan kode poli
        $kodeAwal = substr($poli->kode, 0, 2); // Misal, "PA" dari "PA01"

        // Hitung jumlah pasien yang memiliki rekam medis berdasarkan awalan kode dan pegawai_id
        $rmPasien = DB::table('rekam_mediks')
            ->join('polis', 'rekam_mediks.poli_id', '=', 'polis.id')
            ->where('polis.id', $poli->id)
            ->where('polis.kode', 'LIKE', $kodeAwal . '%')
            ->where('polis.pegawai_id', $pegawai->id)
            ->groupBy('rekam_mediks.pasien_id')
            ->count();

        // Hitung total rekam medis berdasarkan awalan kode dan pegawai_id
        $rmCount = DB::table('rekam_mediks')
            ->join('polis', 'rekam_mediks.poli_id', '=', 'polis.id')
            ->where('polis.id', $poli->id)
            ->where('polis.kode', 'LIKE', $kodeAwal . '%')
            ->where('polis.pegawai_id', $pegawai->id)
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


    public function rekammedik()
    {
        // Mengambil ID user yang sedang login
        $userid = Auth::user()->id;

        // Mencari pegawai berdasarkan user_id
        $pegawai = Pegawai::where('user_id', $userid)->first();

        // Pastikan pegawai ditemukan
        if (!$pegawai) {
            abort(403, 'Pegawai Tidak Ditemukan');
        }

        // Mencari poli berdasarkan pegawai_id
        $poli = Poli::where('pegawai_id', $pegawai->id)->first();

        // Pastikan poli ditemukan
        if (!$poli) {
            abort(403, 'Poli Tidak Ditemukan');
        }

        // Mendapatkan awalan kode dari poli
        $kodeAwal = substr($poli->kode, 0, 2); // Mengambil awalan kode, misal "PA" dari "PA01"

        // Mengambil data rekam medik berdasarkan awalan kode dan pegawai_id
        $rekammedik = RekamMedik::with(['dokter', 'poli', 'pasien'])
            ->whereHas('poli', function ($query) use ($pegawai, $kodeAwal) {
                // Filter poli berdasarkan awalan kode dan pegawai_id
                $query->where('pegawai_id', $pegawai->id)
                    ->where('kode', 'LIKE', $kodeAwal . '%'); // Awalan kode diikuti karakter lain
            })
            ->orderBy('tanggal', 'DESC') // Urutkan berdasarkan tanggal
            ->paginate(10); // Paginasi, 10 data per halaman

        // Menampilkan data melalui Inertia
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
