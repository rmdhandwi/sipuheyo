<?php

namespace App\services;

use App\Http\Requests\PoliRequest;
use App\Models\Poli;
use Error;
use Illuminate\Support\Facades\DB;

class PoliService
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
        $result = Poli::with('dokter')->paginate($perPage);
        return $result;
    }

    public function data(){
        $result = Poli::with('dokter')->get();
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
            $kode = Poli::generateKode($req['nama']);
            $result =  Poli::create([
                'kode' => $kode,
                'nama' => $req['nama'],
                'penyakit' => $req['penyakit'],
                'keterangan' => $req['keterangan'],
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
        DB::beginTransaction();
        try {
            // Temukan data Poli berdasarkan ID
            $data = Poli::find($id);
            if (!$data) {
                throw new Error("Data Poli Tidak Ditemukan!");
            }

            // Periksa apakah nama diubah
            if ($req['nama'] !== $data->nama) {
                // Nama diubah, generate kode baru
                $kode = Poli::generateKode($req['nama']);
            } else {
                // Nama tidak diubah, gunakan kode lama
                $kode = $data->kode;
            }

            // Perbarui data menggunakan mass assignment
            $data->update([
                'kode' => $kode,
                'nama' => $req['nama'],
                'penyakit' => $req['penyakit'] ?? $data->penyakit,
                'keterangan' => $req['keterangan'] ?? $data->keterangan,
                'dokter_id' => $req['dokter_id'] ?? $data->dokter_id,
                'pegawai_id' => $req['pegawai_id'] ?? $data->pegawai_id,
            ]);

            DB::commit();
            return true;
        } catch (\Throwable $th) {
            DB::rollBack();
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
