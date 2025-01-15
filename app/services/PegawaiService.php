<?php

namespace App\services;

use Error;
use App\Models\User;
use App\Models\Pegawai;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\PegawaiRequest;

class PegawaiService
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
        $result = Pegawai::paginate(10); // Mengambil 10 data per halaman
        return $result;
    }

    public function data()
    {
        $result = Pegawai::all();
        return $result;
    }

    public function getById($id)
    {
        $result = Pegawai::find($id);
        return $result;
    }


    public function post(PegawaiRequest $req)
    {

        DB::beginTransaction();
        try {

            //create User As Pegawai
            $user = User::create([
                'name' => strtoupper($req->nama),
                'email' => strtolower($req->email),
                'password' => Hash::make($req->email),
                'role' => 'pegawai',
            ]);

            $result =  Pegawai::create([
                'nama' => strtoupper($req['nama']),
                'jk' => $req['jk'],
                'email' => strtolower($req['email']),
                'bagian' => ucwords($req['bagian']),
                'kontak' => $req['kontak'],
                'user_id' => $user->id,
            ]);

            DB::commit();
            return $result;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw new Error($th->getMessage());
        }
    }

    public function put(PegawaiRequest $req, $id)
    {
        try {
            $data = Pegawai::find($id);
            if (!$data) {
                throw new Error("Data Pegawai Tidak Ditemukan!");
            }

            $data->nama = strtoupper($req['nama']);
            $data->jk = $req['jk'];
            $data->bagian = ucwords($req['bagian']);
            $data->kontak = $req['kontak'];
            $data->save();
            return true;
        } catch (\Throwable $th) {
            throw new Error($th->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            $data = Pegawai::find($id);
            if (!$data) {
                throw new Error("Data Pasien Tidak Ditemukan!");
            }

            // Menyimpan user_id yang terkait dengan pasien
            $userId = $data->user_id;

            // Menghapus data pasien
            $data->delete();

            // Menghapus data user yang terkait
            $user = User::find($userId);
            if ($user) {
                $user->delete();
            }

            return true;
        } catch (\Throwable $th) {
            throw new Error($th->getMessage());
        }
    }
}
