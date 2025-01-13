<?php

namespace App\services;

use App\Http\Requests\PoliRequest;
use App\Models\Poli;
use Error;

class PoliService
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
        $result = Poli::with('dokter')->get();
        foreach ($result as $key => $poli) {
            $poli->dokter;
        }
        return $result;
    }

    public function getById($id)
    {
        $result = Poli::find($id);
        return $result;
    }


    public function post(PoliRequest $req)
    {
        try {
            $result =  Poli::create([
                'kode' => $req['kode'],
                'nama' => $req['nama'],
                'penyakit' => $req['penyakit'],
                'keterangan' => $req['keterangan'],
                'jenis' => $req['jenis'],
                'dokter_id' => $req['dokter_id'],
                'pegawai_id' => $req['pegawai_id'],
            ]);
            return $result;
        } catch (\Throwable $th) {
            throw new Error($th->getMessage());
        }
    }

    public function put(PoliRequest $req, $id)
    {
        try {
            $data = Poli::find($id);
            if (!$data) {
                throw new Error("Data Poli Tidak Ditemukan!");
            }
            $data->kode = $req['kode'];
            $data->nama = $req['nama'];
            $data->penyakit = $req['penyakit'];
            $data->keterangan = $req['keterangan'];
            $data->jenis = $req['jenis'];
            $data->dokter_id = $req['dokter_id'];
            $data->pegawai_id = $req['pegawai_id'];
            $data->save();
            return true;
        } catch (\Throwable $th) {
            throw new Error($th->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            $data = Poli::find($id);
            if (!$data)
                throw new Error("Data Tidak Ditemukan !");

            $data->delete();
            return true;
        } catch (\Throwable $th) {
            throw new Error($th->getMessage());
        }
    }
}
