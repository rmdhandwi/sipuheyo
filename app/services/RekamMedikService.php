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



    public function getByPoli($poliId, $paginate = 10)
    {
        // Ambil data rekam medis beserta relasinya
        $result = RekamMedik::with(['dokter', 'poli', 'pasien'])
            ->where("poli_id", $poliId)
            ->orderBy('tanggal', 'DESC')
            ->paginate($paginate);

        return $result;
    }


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
            $sekarang = Carbon::now('Asia/Jayapura');
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

            $data->kondisi = json_encode($req['kondisi']);
            $data->keluhan = json_encode($req['keluhan']);
            $data->penanganan = json_encode($req['penanganan']);
            $data->resep = json_encode($req['resep']);
            $data->resep_manual = $req['resep_manual'];
            $data->konsultasi_berikut = $req['konsultasi_berikut'];
            $data->status = 'dokter';

            $data->save();
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

    // public function infoKunjunganBerikut()
    // {
    //     try {
    //         $data = RekamMedik::where('konsultasi_berikut', "<>", null)
    //             ->where(function ($q) {
    //                 $q->where('kirimpesan1', null)
    //                     ->orWhere('kirimpesan2', null);
    //             })->get();

    //         $sekarang =  Carbon::create(date("d/m/Y H:s"));
    //         $sekarang->setTimezone("Asia/Jayapura");
    //         foreach ($data as $key => $rm) {
    //             $rm->konsultasi_berikut->setTimezone('Asia/Jayapura');
    //             $diff  = date_diff($sekarang, $rm->konsultasi_berikut);
    //             if ($diff->h <= 24 && !$rm->kirimpesan1) {
    //                 $sended = $this->sendWA($rm);
    //                 if ($sended) {
    //                     $rm->kirimpesan1 = $sekarang;
    //                     $rm->save();
    //                 }
    //             } else if ($diff->h <= 3 && !$rm->kirimpesan2) {
    //                 $sended = $this->sendWA($rm);
    //                 if ($sended) {
    //                     $rm->kirimpesan2 = $sekarang;
    //                     $rm->save();
    //                 }
    //             }
    //         }
    //     } catch (\Throwable $th) {
    //         Log::error($th->getMessage());
    //     }
    // }

    // public function sendWA($rm)
    // {
    //     try {
    //         $pasien = $rm->pasien;
    //         $poli = $rm->poli;
    //         $pesan = 'Bapak/Ibu ' . $pasien->nama . ' Kami mengingatkan kembali untuk jadwal konsultasi pemeriksaan '
    //             . $poli->penyakit . ' akan dilakukan pada tanggal ' . $rm->konsultasi_berikut . '. terimakasih.';
    //         $data = [
    //             "userkey" => env('ZIVA_USERKEY', ''),
    //             "passkey" => env('ZIVA_PASSKEY'),
    //             "to" => $pasien->kontak,
    //             "message" => $pesan
    //         ];

    //         $response = Http::post('https://console.zenziva.net/wareguler/api/sendWA/', $data);
    //         if ($response->successful()) {
    //             Log::info("sended ");
    //             return true;
    //         } else {
    //             $users = $response->json();
    //             Log::info("error sended ");
    //             return false;
    //         }
    //     } catch (\Throwable $th) {
    //         throw $th;
    //     }
    // }
}
