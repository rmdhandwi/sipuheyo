<?php

namespace App\services;

use Error;
use App\Models\User;
use App\Models\Dokter;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\DokterRequest;
use App\Models\Poli;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class DokterService
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
        $result = Dokter::paginate(10);
        return $result;
    }

    public function data()
    {
        $result = Dokter::all();
        return $result;
    }

    public function getById($id)
    {
        $result = Dokter::find($id);
        return $result;
    }

    public function getPoli()
    {
        $user  =  Auth::user();
        $dokter = Dokter::where("user_id", $user->id)->first();
        $poli = Poli::where("dokter_id", $dokter->id)->first();
        return $poli;
    }


    public function post(DokterRequest $req)
    {
        DB::beginTransaction();
        try {

            //create User As Dokter
            $user = User::create([
                'name' => strtoupper($req->nama),
                'email' => $req->email,
                'password' => Hash::make($req->email),
                'role' => 'dokter',
            ]);

            $result =  Dokter::create([
                'nid' => $req['nid'],
                'nama' => strtoupper($req['nama']),
                'email' => $req['email'],
                'jk' => $req['jk'],
                'spesialis' => ucwords($req['spesialis']),
                'kontak' => $req['kontak'],
                'user_id' => $user['id'],
            ]);

            DB::commit();
            return $result;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw new Error($th->getMessage());
        }
    }

    public function put(DokterRequest $req, $id)
    {
        try {
            $dokter = Dokter::find($id);
            if (!$dokter) {
                throw new Error("Data Dokter Tidak Ditemukan!");
            }

            // Jika dokter memiliki user terkait, perbarui email di tabel users
            if ($dokter->user_id) {
                $user = User::find($dokter->user_id);
                if ($user) {
                    $user->email = $req['email'];
                    $user->name = strtoupper($req['nama']);
                    $user->save();
                }
            }

            $dokter->nid = $req['nid'];
            $dokter->nama = strtoupper($req['nama']);
            $dokter->email = $req['email'];
            $dokter->jk = $req['jk'];
            $dokter->spesialis = ucwords($req['spesialis']);
            $dokter->kontak = $req['kontak'];
            $dokter->save();
            return true;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function delete($id)
    {
        try {
            $data = Dokter::find($id);
            if (!$data)
                throw new Error("Data Tidak Ditemukan !");

            $data->delete();
            return true;
        } catch (\Throwable $th) {
            throw new Error($th->getMessage());
        }
    }
}
