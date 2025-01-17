<?php

namespace App\Http\Requests;

use App\Models\Pegawai;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class PegawaiRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // Mendapatkan pegawai dari route parameter
        $pegawaiId = request()->route('id');

        // Menemukan data pasien berdasarkan id
        $pegawai = Pegawai::find($pegawaiId);

        // Jika pegawai ditemukan, ambil user_id
        $userId = $pegawai ? $pegawai->user_id : null;

        return [
            'nama' => ['required', 'string', 'max:100'],
            'nip' => [
                'required',
                'numeric',
                'digits:18',
                Rule::unique('pegawais')->ignore($pegawaiId, 'id')
            ],
            'jk' => ['required', 'in:Laki-Laki,Perempuan'],
            'email' => [
                'required',
                'email',
                function ($attribute, $value, $fail) use ($userId, $pegawaiId) {
                    // Periksa keberadaan email di tabel users
                    $existsInUsers = DB::table('users')
                        ->where('email', $value)
                        ->when($userId, function ($query, $userId) {
                            $query->where('id', '!=', $userId);
                        })
                        ->exists();

                    // Periksa keberadaan email di tabel pegawai
                    $existsInPasiens = DB::table('pasiens')
                        ->where('email', $value)
                        ->when($pegawaiId, function ($query, $pegawaiId) {
                            $query->where('id', '!=', $pegawaiId);
                        })
                        ->exists();

                    // Jika email ditemukan di salah satu tabel, gagal
                    if ($existsInUsers || $existsInPasiens) {
                        $fail(__('Email ini sudah digunakan oleh pengguna lain.'));
                    }
                }
            ],
            'bagian' => ['required', 'string'],
            'kontak' => [
                'required',
                'numeric',
                'digits_between:11,13',
                Rule::unique('pegawais')->ignore($pegawaiId, 'id'),
            ]
        ];
    }

    public function messages(): array
    {
        return [
            'nama.required' => 'Kolom Nama wajib diisi.',
            'nama.string' => 'Nama harus berupa teks.',
            'nama.max' => 'Nama maksimal 100 karakter.',

            'nip.required' => 'Kolom NIP tidak boleh kosong.',
            'nip.numeric' => 'Kolom NIP harus berupa angka.',
            'nip.digits' => 'Kolom NIP harus terdiri dari 18 digit.',
            'nip.unique' => 'Nip ini sudah digunakan oleh pengguna lain',

            'jk.required' => 'Kolom Jenis Kelamin wajib diisi.',
            'jk.in' => 'Kolom Jenis Kelamin hanya boleh berisi Laki-laki atau Perempuan.',

            'email.required' => 'Kolom Email wajib diisi.',
            'email.email' => 'Email harus berformat valid (contoh: user@example.com).',

            'bagian.required' => 'Kolom Bagian wajib diisi.',
            'bagian.string' => 'Bagian harus berupa teks.',

            'kontak.required' => 'Kolom Kontak wajib diisi.',
            'kontak.numeric' => 'Kontak harus berupa angka.',
            'kontak.digits_between' => 'Kolom Kontak harus berisi antara 11 hingga 13 digit.',
            'kontak.unique' => 'Kontak ini sudah digunakan oleh pengguna lain.',
        ];
    }
}
