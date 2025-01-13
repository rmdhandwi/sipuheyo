<?php

namespace App\services;

use App\Http\Requests\RekamMedikRequest;
use App\Models\Poli;
use App\Models\RekamMedik;
use Carbon\Carbon;
use Error;
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

    public function all()
    {
        $result = RekamMedik::all();
        foreach ($result as $key => $rekamMedik) {
            $rekamMedik->dokter;
            $rekamMedik->poli;
            $rekamMedik->pasien;
        }
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

        $results = RekamMedik::where('poli_id', $poliId)
            ->get();
        foreach ($results as $key => $result) {
            $result->poli;
            $result->dokter;
            $result->pasien;
        }
        return $results;
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
        $result = RekamMedik::Where("pasien_id", $id)->get();
        foreach ($result as $key => $rekamMedik) {
            $rekamMedik->poli;
            $rekamMedik->dokter;
        }

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

    public function post(RekamMedikRequest $req)
    {
        try {
            $sekarang =  Carbon::create(date("Y/m/d"));
            $sekarang->setTimezone("Asia/Jayapura");

            $data = RekamMedik::where('tanggal', $sekarang)->count();

            $poli = Poli::find($req['poli_id']);
            $antrian = $poli->kode . '-' . $sekarang->format('dmY') . '-' . str_pad($data + 1, 3, '0', STR_PAD_LEFT);
            $result =  RekamMedik::create([
                'tanggal' => $req['tanggal'],
                'antrian' => $antrian,
                'poli_id' => $req['poli_id'],
                'pasien_id' => $req['pasien_id'],
                'dokter_id' => $req['dokter_id'],
                'status' => 'baru',
                'keluhan' => json_encode($req['keluhan']),
                'penanganan' => json_encode($req['penanganan']),
                'resep' => json_encode($req['resep'])
            ]);

            Log::info("success insert" . $result->id);
            return $result;
        } catch (\Throwable $th) {
            Log::info($th->getMessage());
            throw new Error($th->getMessage());
        }
    }

    public function put(RekamMedikRequest $req, $id)
    {
        try {
            $data = RekamMedik::find($id);
            if (!$data) {
                throw new Error("Data RekamMedik Tidak Ditemukan!");
            }
            $data->tanggal = $req['tanggal'];
            $data->poli_id = $req['poli_id'];
            $data->dokter_id = $req['dokter_id'];
            $data->pasien_id = $req['pasien_id'];
            $data->konsultasi_berikut = $req['konsultasi_berikut'];
            $data->kondisi = $req['kondisi'];
            $data->keluhan = $req['keluhan'];
            $data->penanganan = $req['penanganan'];
            $data->resep = $req['resep'];
            $data->status = $req['status'];
            if ($req['hasil_lab']) {
                $data->hasil_lab = $req['hasil_lab'];
            }
            $data->save();
            $this->infoKunjunganBerikut();
            return true;
        } catch (\Throwable $th) {
            throw new Error($th->getMessage());
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

            $sekarang =  Carbon::create(date("Y/m/d H:s"));
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
