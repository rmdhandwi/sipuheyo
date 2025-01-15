<?php

namespace App\services;

use App\Http\Requests\PasienRequest;
use App\Models\Dokter;
use App\Models\Pasien;
use App\Models\RekamMedik;
use App\Models\User;
use Error;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class PasienService
{
    /**
     * Create a new class instance.
     */


    public function __construct(RekamMedikService $rm) {}

    public function all()
    {
        $result = Pasien::paginate(10);
        return $result;
    }

    public function data()
    {
        $result = Pasien::all();
        return $result;
    }

    public function getByDokterId($dokterId)
    {
        $data = RekamMedik::Where("dokter_id", $dokterId)
            ->orderBy('tanggal')
            ->get();
        $pasien = [];
        foreach ($data as $key => $rm) {
            $pasien[] = $rm->pasien;
        }
        return $pasien;
    }

    public function getById($id)
    {
        $result = Pasien::find($id);
        return $result;
    }


    public function post(PasienRequest $req)
    {
        DB::beginTransaction();
        try {
            // Buat user baru
            $user = User::create([
                'name' => $req['nama'],
                'email' => $req['email'],
                'password' => Hash::make($req['email']),
                'role' => 'pasien',
            ]);

            // Buat data pasien baru
            $result = Pasien::create([
                'nama' => $req['nama'],
                'nik' => $req['nik'],
                'email' => $req['email'],
                'jk' => $req['jk'],
                'tempat_lahir' => $req['tempat_lahir'],
                'tanggal_lahir' => $req['tanggal_lahir'],
                'alamat' => $req['alamat'],
                'kontak' => $req['kontak'],
                'user_id' => $user->id,
            ]);

            DB::commit();
            return response()->json(['message' => 'Data pasien berhasil disimpan!', 'data' => $result], 201);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['message' => 'Gagal menyimpan data pasien.', 'error' => $th->getMessage()], 500);
        }
    }

    public function put(PasienRequest $req, $id)
    {
        try {
            $data = Pasien::find($id);
            if (!$data) {
                throw new Error("Data Pasien Tidak Ditemukan!");
            }

            $data->nama = $req['nama'];
            $data->nik = $req['nik'];
            $data->jk = $req['jk'];
            $data->tempat_lahir = $req['tempat_lahir'];
            $data->tanggal_lahir = $req['tanggal_lahir'];
            $data->alamat = $req['alamat'];
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
            $data = Pasien::find($id);
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
