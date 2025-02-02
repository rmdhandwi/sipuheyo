<?php

namespace App\services;

use App\Http\Requests\PoliRekamMedikRequest;
use App\Http\Requests\DokterRekamMedikRequest;
use App\Http\Requests\RekamMedikRequest;
use App\Models\Pasien;
use App\Models\Poli;
use App\Models\RekamMedik;
use Carbon\Carbon;
use Error;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RekamMedikService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function all($perPage = 10)
    {
        $result = RekamMedik::with(['dokter', 'poli', 'pasien'])
            ->orderBy('tanggal', 'DESC')
            ->paginate($perPage);

        return $result;
    }

    public function data()
    {
        $result = RekamMedik::with(['dokter', 'poli', 'pasien'])->get();
        return $result;
    }

    public function getById($id)
    {
        $result = RekamMedik::find($id);
        $result->poli;
        $result->dokter;
        $result->pasien;

        return $result;
    }

    public function getByDetailId($id)
    {
        $result = RekamMedik::with(['dokter', 'pasien', 'poli'])
            ->where('id', $id)
            ->get();
        return $result;
    }

    public function getByDetailPasienId($id)
    {
        $result = RekamMedik::with(['dokter', 'pasien', 'poli'])
            ->where('pasien_id', $id)
            ->get();

        return $result;
    }



    // public function getByPoli($poliId, $paginate = 10)
    // {
    //     // Ambil data rekam medis beserta relasinya
    //     $result = RekamMedik::with(['dokter', 'poli', 'pasien'])
    //         ->where("poli_id", $poliId)
    //         ->orderBy('tanggal', 'DESC')
    //         ->paginate($paginate);

    //     return $result;
    // }


    public function getByPoliAndDate($poliId, $date)
    {
        $start = Carbon::create($date);
        $end = $start->addDay(1);


        $results = RekamMedik::where('poli_id', $poliId)
            ->whereDate('konsultasi_berikut', '=', $date)
            ->get();
        foreach ($results as $key => $result) {
            $result->poli;
            $result->dokter;
            $result->pasien;
        }
        return $results->toJson();
    }



    public function getByPoliAndTanggal($poliId, $date)
    {
        $start = Carbon::create($date);
        $end = $start->addDay(1);


        $results = RekamMedik::where('poli_id', $poliId)
            ->whereDate('tanggal', '=', $date)
            ->get();
        foreach ($results as $key => $result) {
            $result->poli;
            $result->dokter;
            $result->pasien;
        }
        return $results->toJson();
    }


    public function getByPasienId($id, $paginate = 10)
    {
        $result = RekamMedik::with(['dokter', 'poli', 'pasien'])
            ->where("pasien_id", $id)
            ->paginate($paginate);
        return $result;
    }


    public function getByDetailPasien($pasienId, $poliId)
    {
        // Ambil data rekam medis berdasarkan pasien_id dan poli, dengan data pasien digrupkan
        return RekamMedik::with('pasien', 'dokter', 'poli')
            ->where('pasien_id', $pasienId)
            ->where('poli_id', $poliId)
            ->selectRaw('
            pasien_id,
            poli_id,
            COUNT(*) as total_pemeriksaan,
            MAX(tanggal) as terakhir_diperiksa
        ')
            ->groupBy('pasien_id', 'poli_id') // Pastikan semua kolom non-agregat ada di klausa groupBy
            ->get();
    }


    public function getByDokterId($id, $paginate = 10)
    {
        $result = RekamMedik::with('dokter', 'pasien', 'poli')
            ->where("dokter_id", $id)
            ->where('status', '=', 'poli')
            ->orWhere('status', '=', 'dokter')
            ->orderBy('tanggal', 'DESC')
            ->paginate($paginate);
        return $result;
    }

    public function getPasienByPoli($poliId, $paginate = 10)
    {
        // Ambil data rekam medik berdasarkan pegawai, dikelompokkan berdasarkan pasien_id
        $dataPasien = RekamMedik::with('pasien')
            ->where('poli_id', $poliId)
            ->selectRaw('pasien_id, COUNT(*) as total_pemeriksaan, MAX(tanggal) as terakhir_diperiksa')
            ->groupBy('pasien_id')
            ->orderBy('terakhir_diperiksa', 'desc') // Mengurutkan berdasarkan tanggal terakhir diperiksa
            ->paginate($paginate);

        return $dataPasien;
    }


    public function antrian(RekamMedikRequest $req)
    {
        try {
            // $sekarang = Carbon::now('Asia/Jayapura');
            $sekarang = Carbon::createFromFormat('Y-m-d H:i:s', '2025-01-31 10:05:00', 'Asia/Jayapura');
            $jamSekarang = (int) $sekarang->format('H');

            // Validasi jam pendaftaran
            if ($jamSekarang < 5 || $jamSekarang > 24) {
                throw new \Exception("Pendaftaran hanya dibuka antara pukul 05:00 hingga 10:00 WIT.");
            }

            $user = auth()->user();
            if (!in_array($user->role, ['pasien', 'admin'])) {
                throw new \Exception("Hanya pasien dan admin yang dapat mendaftar antrian.");
            }

            // Jika role admin, ambil pasien_id dari request
            if ($user->role === 'admin') {
                $pasien = Pasien::findOrFail($req->pasien_id);
                if (!$pasien) {
                    throw new \Exception("Pasien tidak ditemukan.");
                }
            } else {
                // Jika role pasien, ambil pasien berdasarkan user_id
                $pasien = Pasien::where('user_id', $user->id)->first();
                if (!$pasien) {
                    throw new \Exception("Pasien tidak ditemukan.");
                }
            }

            $antrianHariIni = RekamMedik::where('tanggal', $sekarang->toDateString())
                ->where('pasien_id', $pasien->id)
                ->where('poli_id', $req->poli_id)
                ->exists();

            if ($antrianHariIni) {
                throw new \Exception("Anda telah mendaftar di poli ini untuk hari ini.");
            }

            $jumlahAntrian = RekamMedik::where('tanggal', $sekarang->toDateString())
                ->where('poli_id', $req->poli_id)
                ->count();

            if ($jumlahAntrian >= 10) {
                throw new \Exception("Nomor antrean di poli ini telah penuh.");
            }

            // Menangani exception untuk pasien
            try {
                $pasien = Pasien::findOrFail($req->pasien_id);
            } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
                throw new \Exception("Pasien tidak ditemukan.");
            }

            // Menangani exception untuk poli
            try {
                $poli = Poli::findOrFail($req->poli_id);
            } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
                throw new \Exception("Poli tidak ditemukan.");
            }

            $antrian = $poli->kode . '-' . $sekarang->format('dmY') . '-' . str_pad($jumlahAntrian + 1, 2, '0', STR_PAD_LEFT);

            return RekamMedik::create([
                'tanggal' => $sekarang->toDateString(),
                'antrian' => $antrian,
                'poli_id' => $req->poli_id,
                'pasien_id' => $pasien->id,
                'dokter_id' => $req->dokter_id,
                'konsultasi_berikut' => null,
                'status' => 'baru',
                'keluhan' => json_encode($req->keluhan),
                'penanganan' => json_encode($req->penanganan),
                'resep' => json_encode($req->resep),
            ]);
        } catch (\Throwable $th) {
            throw new \Exception($th->getMessage());
        }
    }



    public function put(PoliRekamMedikRequest $req, $id)
    {
        try {
            DB::beginTransaction();

            $data = RekamMedik::find($id);
            if (!$data) {
                throw new \Exception("Data RekamMedik tidak ditemukan.");
            }

            $user = auth()->user();
            if ($user->role !== 'pegawai') {
                throw new \Exception("Hanya pegawai yang dapat mengakses.");
            }

            $status = $req['status'] = 'poli';

            $data->kondisi = json_encode($req['kondisi']); // Pastikan dalam format JSON
            $data->keluhan = json_encode($req['keluhan']); // Pastikan dalam format JSON
            $data->status = $status;

            if (isset($req['hasil_lab'])) {
                $data->hasil_lab = $req['hasil_lab'];
            }

            $data->save();
            DB::commit();
            return true;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw new \Exception($th->getMessage());
        }
    }

    public function dokterput(DokterRekamMedikRequest $req, $id)
    {
        try {
            DB::beginTransaction();

            $data = RekamMedik::find($id);
            if (!$data) {
                throw new \Exception("Data RekamMedik tidak ditemukan.");
            }

            $user = auth()->user();
            if ($user->role !== 'dokter') {
                throw new \Exception("Hanya Dokter yang dapat mengakses.");
            }

            // Ambil data dari request
            $konsultasiBerikut = $req['konsultasi_berikut'];

            // Mengonversi menjadi format datetime menggunakan Carbon
            $konsultasiBerikutDatetime = Carbon::parse($konsultasiBerikut);

            // dd($konsultasiBerikutDatetime);

            $data->kondisi = json_encode($req['kondisi']);
            $data->keluhan = json_encode($req['keluhan']);
            $data->penanganan = json_encode($req['penanganan']);
            $data->resep = json_encode($req['resep']);
            $data->resep_manual = $req['resep_manual'];
            $data->konsultasi_berikut = $konsultasiBerikutDatetime;
            $data->status = 'dokter';

            $data->save();
            // $this->infoKunjunganBerikut();
            DB::commit();
            return true;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw new \Exception($th->getMessage());
        }
    }


    public function delete($id)
    {
        try {
            $data = RekamMedik::find($id);
            if (!$data)
                throw new Error("Data Tidak Ditemukan !");

            $data->delete();
            return true;
        } catch (\Throwable $th) {
            throw new Error($th->getMessage());
        }
    }

    public function infoKunjunganBerikut()
    {
        try {
            Log::info('Memulai pengecekan kunjungan berikut...');

            // Ambil data rekam medis yang memiliki jadwal konsultasi berikut yang tidak null
            $data = RekamMedik::whereNotNull('konsultasi_berikut')
                ->where(function ($q) {
                    $q->whereNull('kirimpesan1') // Belum kirim pesan pertama (12 jam sebelum)
                        ->orWhere(function ($q2) {
                            $q2->whereNotNull('kirimpesan1') // Sudah kirim pesan pertama
                                ->whereNull('kirimpesan2');  // Tapi belum kirim pesan kedua
                        });
                })->get();

            $sekarang = Carbon::now('Asia/Jayapura'); // Waktu sekarang dengan zona waktu Jayapura
            // $sekarang = Carbon::createFromFormat('Y-m-d H:i:s', '2025-01-31 18:05:00', 'Asia/Jayapura');
            foreach ($data as $rm) {
                $jadwalKonsultasi = Carbon::parse($rm->konsultasi_berikut, 'Asia/Jayapura');
                $diffHours = $sekarang->diffInHours($jadwalKonsultasi, false);

                // Jika waktu konsultasi masih lebih dari 12 jam, cek apakah bisa kirim pesan 1
                if ($diffHours <= 12 && $diffHours > 3 && is_null($rm->kirimpesan1)) {
                    if ($this->sendWA($rm, 1)) {
                        $rm->kirimpesan1 = $sekarang;
                        $rm->save();
                        Log::info("Pesan 1 dikirim ke: {$rm->pasien->kontak} ({$rm->pasien->nama})");
                    }
                }
                // Jika sudah mendekati 3 jam terakhir, cek apakah bisa kirim pesan 2
                elseif ($diffHours <= 3 && is_null($rm->kirimpesan2) && !is_null($rm->kirimpesan1)) {
                    if ($this->sendWA($rm, 2)) {
                        $rm->kirimpesan2 = $sekarang;
                        $rm->save();
                        Log::info("Pesan 2 dikirim ke: {$rm->pasien->kontak} ({$rm->pasien->nama})");
                    }
                } else {
                    Log::info("Konsultasi berikutnya tidak ditemukan");
                }
            }
        } catch (\Throwable $th) {
            Log::error("Error dalam infoKunjunganBerikut: " . $th->getMessage());
        }
    }

    public function sendWA($rm, $tipePesan)
    {
        try {
            $pasien = $rm->pasien;
            $dokter = $rm->dokter;
            $poli = $rm->poli;

            // Format nomor WhatsApp ke +62 jika belum sesuai
            $kontak = $pasien->kontak;
            if (substr($kontak, 0, 1) === '0') {
                $kontak = '+62' . substr($kontak, 1);
            }

            // Format tanggal dan waktu konsultasi
            $formattedDate = Carbon::parse($rm->konsultasi_berikut, 'Asia/Jayapura')->isoFormat('dddd, D MMMM YYYY');
            $formattedTime = Carbon::parse($rm->konsultasi_berikut, 'Asia/Jayapura')->isoFormat('HH:mm');

            // Pesan pengingat
            $pesanTambahan = ($tipePesan == 1)
                ? "🔔 Ini adalah pengingat 12 jam sebelum konsultasi."
                : "⏳ Ini adalah pengingat terakhir 3 jam sebelum konsultasi.";

            $pesan = "🌟 *Pengingat Konsultasi* 🌟\n\n"
                . "Bapak/Ibu *{$pasien->nama}*,\n\n"
                . "Kami dari *PUSKESMAS HEBEYBHULU YOKA* ingin mengingatkan kembali tentang jadwal konsultasi pemeriksaan *{$poli->penyakit}* di *{$poli->nama}*.\n\n"
                . "🩺 *Dokter:* {$dokter->nama}\n"
                . "📅 *Tanggal:* {$formattedDate}\n"
                . "🕒 *Jam:* {$formattedTime} WIT\n\n"
                . "{$pesanTambahan}\n\n"
                . "🙏 Semoga sehat selalu!";

            $data = [
                "userkey" => env('ZIVA_USERKEY', ''),
                "passkey" => env('ZIVA_PASSKEY'),
                "to" => $kontak,
                "message" => $pesan
            ];

            $response = Http::post('https://console.zenziva.net/wareguler/api/sendWA/', $data);

            if ($response->successful()) {
                Log::info("Pesan WA berhasil dikirim ke {$kontak}");
                return true;
            } else {
                Log::error("Gagal mengirim pesan ke {$kontak}: " . json_encode($response->json()));
                return false;
            }
        } catch (\Throwable $th) {
            Log::error("Error dalam sendWA: " . $th->getMessage());
            return false;
        }
    }
}
