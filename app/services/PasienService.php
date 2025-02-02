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

    public function getById($id)
    {
        $result = Pasien::find($id);
        return $result;
    }

    public function post(PasienRequest $req)
    {
        DB::beginTransaction();
        try {

            // dd($req->all());
            // Jika email tidak diisi, buat email dari nama, jika diisi gunakan email inputan
            $email = $req['email'] ?? $this->generateEmailFromName($req['nama']);

            // Buat user baru
            $user = User::create([
                'name' => strtoupper($req['nama']),
                'email' => $email,
                'password' => Hash::make($req['kontak']),
                'role' => 'pasien',
            ]);

            // Buat data pasien baru
            $result = Pasien::create([
                'nama'      => strtoupper($req['nama']),
                'nik'       => $req['nik'],
                'rekammedik'=> strtoupper($req['rekammedik']),
                'layanan'   => $req['layanan'],
                'email'     => $email,
                'jk'        => $req['jk'],
                'tempat_lahir'  => ucfirst($req['tempat_lahir']),
                'tanggal_lahir' => $req['tanggal_lahir'],
                'alamat'        => ucwords($req['alamat']),
                'kontak'        => $req['kontak'],
                'user_id'       => $user->id,
            ]);

            DB::commit();
            return response()->json(['message' => 'Data pasien berhasil disimpan!', 'data' => $result], 201);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['message' => 'Gagal menyimpan data pasien.', 'error' => $th->getMessage()], 500);
        }
    }

    /**
     * Membuat email dari nama pasien.
     *
     * @param string $name
     * @return string
     */
    private function generateEmailFromName(string $name): string
    {
        // Hilangkan spasi dan karakter non-alfanumerik dari nama
        $cleanedName = preg_replace('/[^a-zA-Z0-9]/', '', strtolower($name));

        // Tambahkan domain
        $domain = '@puskesmas.tech';

        // Gabungkan nama dan domain untuk email
        return $cleanedName . $domain;
    }

    public function put(PasienRequest $req, $id)
    {
        try {
            // Temukan data pasien berdasarkan ID
            $pasien = Pasien::find($id);
            if (!$pasien) {
                throw new \Exception("Data Pasien Tidak Ditemukan!");
            }

            // Jika pasien memiliki user terkait, perbarui email di tabel users
            if ($pasien->user_id) {
                $user = User::find($pasien->user_id);
                if ($user) {
                    $user->email = $req['email'];
                    $user->name = strtoupper($req['nama']);
                    $user->password = $req['kontak'];
                    $user->save();
                }
            }

            // Perbarui data pasien
            $pasien->nama = strtoupper($req['nama']);
            $pasien->rekammedik = strtoupper($req['rekammedik']);
            $pasien->nik = $req['nik'];
            $pasien->layanan = $req['layanan'];
            $pasien->email = $req['email'];
            $pasien->jk = $req['jk'];
            $pasien->tempat_lahir = ucfirst($req['tempat_lahir']);
            $pasien->tanggal_lahir = $req['tanggal_lahir'];
            $pasien->alamat = ucwords($req['alamat']);
            $pasien->kontak = $req['kontak'];
            $pasien->save();

            return true;
        } catch (\Throwable $th) {
            throw new \Exception($th->getMessage());
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
