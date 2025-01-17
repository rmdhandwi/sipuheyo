<?php

namespace App\services;

use App\Http\Requests\PoliRekamMedikRequest;
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
        $result = RekamMedik::with(['dokter', 'poli', 'pasien'])->paginate($perPage);
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

    public function getByPoli($poliId)
    {
        // Ambil data rekam medis beserta relasinya
        $result = RekamMedik::with(['dokter', 'poli', 'pasien'])
            ->where("poli_id", $poliId)
            ->get();

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


    public function getByPasienId($id)
    {
        // Ambil data rekam medis beserta relasinya
        $result = RekamMedik::with(['dokter', 'poli', 'pasien'])
            ->where("pasien_id", $id)
            ->get();

        return $result;
    }


    public function getByDokterId($id)
    {
        $result = RekamMedik::Where("dokter_id", $id)
            ->orderBy('tanggal')
            ->get();
        foreach ($result as $key => $rekamMedik) {
            $rekamMedik->poli;
            $rekamMedik->dokter;
            $rekamMedik->pasien;
        }

        return $result;
    }


    public function antrian(RekamMedikRequest $req)
    {
        try {
            $sekarang = Carbon::now('Asia/Jayapura');
            // $sekarang = Carbon::createFromFormat('Y-d-m', '2025-17-01', 'Asia/Jayapura');
            $jamSekarang = (int) $sekarang->format('H');

            // Validasi jam pendaftaran
            if ($jamSekarang < 5 || $jamSekarang > 10) {
                throw new \Exception("Pendaftaran hanya dibuka antara pukul 05:00 hingga 10:00 WIT.");
            }

            $user = auth()->user();
            if (!in_array($user->role, ['pasien', 'admin'])) {
                throw new \Exception("Hanya pasien dan admin yang dapat mendaftar antrian.");
            }

            $antrianHariIni = RekamMedik::where('tanggal', $sekarang->toDateString())
                ->where('pasien_id', $user->id)
                ->where('poli_id', $req->poli_id)
                ->exists();

            if ($antrianHariIni) {
                throw new \Exception("Anda telah mendaftar di poli ini untuk hari ini.");
            }

            $jumlahAntrian = RekamMedik::where('tanggal', $sekarang->toDateString())
                ->where('poli_id', $req->poli_id)
                ->count();

            //  $jumlahAntrian = 10;

            if ($jumlahAntrian >= 10) {
                throw new \Exception("Nomor antrean di poli ini telah penuh.");
            }

            $pasien = Pasien::findOrFail($req->pasien_id);

            $poli = Poli::findOrFail($req->poli_id);
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

            // dd($req->all());

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
            Log::info('Start Check Kunjungan ');
            $data = RekamMedik::where('konsultasi_berikut', "<>", null)
                ->where(function ($q) {
                    $q->where('kirimpesan1', null)
                        ->orWhere('kirimpesan2', null);
                })->get();

            $sekarang =  Carbon::create(date("d/m/Y H:s"));
            $sekarang->setTimezone("Asia/Jayapura");
            foreach ($data as $key => $rm) {
                $rm->konsultasi_berikut->setTimezone('Asia/Jayapura');
                $diff  = date_diff($sekarang, $rm->konsultasi_berikut);
                if ($diff->h <= 24 && !$rm->kirimpesan1) {
                    $sended = $this->sendWA($rm);
                    if ($sended) {
                        $rm->kirimpesan1 = $sekarang;
                        $rm->save();
                    }
                } else if ($diff->h <= 3 && !$rm->kirimpesan2) {
                    $sended = $this->sendWA($rm);
                    if ($sended) {
                        $rm->kirimpesan2 = $sekarang;
                        $rm->save();
                    }
                }
            }
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
        }
    }

    public function sendWA($rm)
    {
        try {
            $pasien = $rm->pasien;
            $poli = $rm->poli;
            $pesan = 'Bapak/Ibu ' . $pasien->nama . ' Kami mengingatkan kembali untuk jadwal konsultasi pemeriksaan '
                . $poli->penyakit . ' akan dilakukan pada tanggal ' . $rm->konsultasi_berikut . '. terimakasih.';
            $data = [
                "userkey" => env('ZIVA_USERKEY', ''),
                "passkey" => env('ZIVA_PASSKEY'),
                "to" => $pasien->kontak,
                "message" => $pesan
            ];

            $response = Http::post('https://console.zenziva.net/wareguler/api/sendWA/', $data);
            if ($response->successful()) {
                Log::info("sended ");
                return true;
            } else {
                $users = $response->json();
                Log::info("error sended ");
                return false;
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
