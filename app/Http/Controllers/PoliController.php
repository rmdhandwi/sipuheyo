<?php

namespace App\Http\Controllers;

use App\Models\Dokter;
use App\Models\Poli;
use Inertia\Inertia;
use App\Models\Pasien;
use App\Models\Pegawai;
use App\Models\RekamMedik;
use App\services\PoliService;
use App\services\DokterService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;

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

        // Cari semua poli berdasarkan pegawai_id
        $poli = Poli::where("pegawai_id", $pegawai->id)->get();
        $poliname = Poli::where("pegawai_id", $pegawai->id)->first();

        // Jika poli tidak ditemukan, logout dan lempar exception
        if ($poli->isEmpty()) {
            Auth::logout();
            abort(403, 'Akses ditolak. Data poli tidak ditemukan. Anda telah logout.');
        }

        // Mendapatkan awalan kode poli dari poli yang ada
        $kodeAwal = substr($poli->first()->kode, 0, 2); // Misal, "PA" dari "PA01"

        // Hitung jumlah pasien yang memiliki rekam medis berdasarkan poli dan pegawai_id
        $rmPasien = DB::table('rekam_mediks')
            ->join('polis', 'rekam_mediks.poli_id', '=', 'polis.id')
            ->whereIn('polis.id', $poli->pluck('id')) // Menggunakan pluck untuk mendapatkan semua poli_id
            ->where('polis.kode', 'LIKE', $kodeAwal . '%')
            ->where('polis.pegawai_id', $pegawai->id)
            ->selectRaw('COUNT(DISTINCT rekam_mediks.pasien_id) as total_pasien') // Menghitung jumlah pasien unik
            ->value('total_pasien'); // Ambil nilai total pasien

        // Hitung total rekam medis berdasarkan poli dan pegawai_id
        $rmCount = DB::table('rekam_mediks')
            ->join('polis', 'rekam_mediks.poli_id', '=', 'polis.id')
            ->whereIn('polis.id', $poli->pluck('id')) // Menggunakan pluck untuk mendapatkan semua poli_id
            ->where('polis.kode', 'LIKE', $kodeAwal . '%')
            ->where('polis.pegawai_id', $pegawai->id)
            ->count(); // Hitung total rekam medis

        return Inertia::render(
            "Poli/Index",
            [
                'pegawai' => $pegawai,
                'poli' => $poliname,
                'resep' => $rmCount,
                'pasienCount' => $rmPasien,
                'rekammedikCount' => $rmCount,
            ]
        );
    }

    public function rekammedik()
    {
        // Mengambil ID user yang sedang login
        $userid = Auth::id();

        // Mencari pegawai berdasarkan user_id
        $pegawai = Pegawai::where('user_id', $userid)->first();
        if (!$pegawai) {
            abort(403, 'Pegawai Tidak Ditemukan');
        }

        // Mencari poli berdasarkan pegawai_id
        $poli = Poli::where('pegawai_id', $pegawai->id)->first();
        if (!$poli) {
            abort(403, 'Poli Tidak Ditemukan');
        }

        // Mendapatkan awalan kode dari poli
        $kodeAwal = substr($poli->kode, 0, 2); // Contoh: "PA" dari "PA01"

        // Mengambil data rekam medis berdasarkan poli
        $rekammedik = RekamMedik::with(['dokter', 'poli', 'pasien'])
            ->selectRaw('
                pasien_id, 
                COUNT(*) as total_rekam_medik, 
                SUM(CASE WHEN status = "baru" THEN 1 ELSE 0 END) as total_status_baru
            ')
            ->whereHas('poli', function ($query) use ($pegawai, $kodeAwal) {
                $query->where('pegawai_id', $pegawai->id)
                    ->where('kode', 'LIKE', $kodeAwal . '%');
            })

            ->groupBy('pasien_id') // Kelompokkan berdasarkan pasien
            ->paginate(10);

        // Menampilkan data melalui Inertia
        return Inertia::render('Poli/RekamMedik', [
            'pegawai' => $pegawai,
            'poli' => $poli,
            'data' => $rekammedik,
        ]);
    }

    public function rmPasien($id)
    {
        // Mengambil ID user yang sedang login
        $userid = Auth::id();

        // Mencari pegawai berdasarkan user_id
        $pegawai = Pegawai::where('user_id', $userid)->first();
        if (!$pegawai) {
            abort(403, 'Pegawai Tidak Ditemukan');
        }

        // Mencari poli berdasarkan pegawai_id
        $poli = Poli::where('pegawai_id', $pegawai->id)->first();
        if (!$poli) {
            abort(403, 'Poli Tidak Ditemukan');
        }

        $dokter = Dokter::all();

        // Mendapatkan awalan kode dari poli
        $kodeAwal = substr($poli->kode, 0, 2);

        // Mengambil data rekam medis hanya untuk pasien dengan ID yang dipassing
        $rekammedikQuery = RekamMedik::with(['dokter', 'poli', 'pasien'])
            ->where('pasien_id', $id) // **Filter berdasarkan pasien yang dipassing**
            ->whereHas('poli', function ($query) use ($pegawai, $kodeAwal) {
                $query->where('pegawai_id', $pegawai->id)
                    ->where('kode', 'LIKE', $kodeAwal . '%');
            })
            ->orderByRaw("FIELD(status, 'Baru', 'Poli','Dokter')") // Status "baru" lebih dulu
            ->orderBy('tanggal', 'DESC')
            ->paginate(10);

        $kode = RekamMedik::with('pasien')->where('pasien_id', $id)->first();

        // Menampilkan data melalui Inertia
        return Inertia::render('Poli/PasienRekamMedik', [
            'pegawai' => $pegawai,
            'poli' => $poli,
            'dokter' => $dokter,
            'data' => $rekammedikQuery,
            'rekammedik' => $kode,
        ]);
    }


    public function daftar(PoliService $poliService, DokterService  $dokterService)
    {
        $userid = Auth::user()->id;
        $pasien = Pasien::where('user_id', $userid)->first();
        return Inertia::render('Pasien/AddRekamMedikPage', ['polis' => $poliService->all(),   'dokters' => $dokterService->all(),  'pasien' => $pasien]);
    }
}
