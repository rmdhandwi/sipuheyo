<?php

namespace App\services;

use App\Http\Requests\ObatRequest;
use App\Jobs\ProcessJadwalBerobat;
use App\Models\Obat;
use Error;

class ObatService
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

        $result = Obat::paginate(10);
        return $result;
    }

    public function data()
    {

        $result = Obat::all();
        return $result;
    }

    public function getById($id)
    {
        $result = Obat::find($id);
        return $result;
    }


    public function post(ObatRequest $req)
    {
        try {
            $result = Obat::create([
                'nama' => strtoupper($req['nama']),
                'merek' => ucwords($req['merek']),
                'dosis' => ucwords($req['dosis']),
                'deskripsi' => ucwords($req['deskripsi']),
                'kemasan' => ucwords($req['kemasan']),
            ]);
            return $result;
        } catch (\Throwable $th) {
            throw new Error($th->getMessage());
        }
    }

    public function put(ObatRequest $req, $id)
    {
        try {
            $data = Obat::find($id);
            if (!$data) {
                throw new Error("Data Obat Tidak Ditemukan!");
            }

            $data->nama = strtoupper($req['nama']);
            $data->merek = ucwords($req['merek']);
            $data->dosis = ucwords($req['dosis']);
            $data->kemasan = ucwords($req['deskripsi']);
            $data->deskripsi = ucwords($req['kemasan']);
            $data->save();
            return true;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function delete($id)
    {
        try {
            $data = Obat::find($id);
            if (!$data)
                throw new Error("Data Tidak Ditemukan !");

            $data->delete();
            return true;
        } catch (\Throwable $th) {
            throw new Error($th->getMessage());
        }
    }
}
