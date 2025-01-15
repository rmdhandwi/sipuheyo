<?php

namespace App\Http\Requests;

use App\Models\Pasien;
use App\Models\User;
use Illuminate\Validation\Rules\Password;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PasienRequest extends FormRequest
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
        // Mendapatkan pasienId dari route parameter
        $pasienId = request()->route('id');
        // Menemukan data pasien berdasarkan id
        $pasien = Pasien::find($pasienId);

        // Jika pasien ditemukan, ambil user_id
        $userId = $pasien ? $pasien->user_id : null;

        // Mengembalikan aturan validasi
        return [
            'nama' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'tempat_lahir' => 'required|string|max:255',
            'jk' => ['required', 'in:Laki-Laki,Perempuan'],
            'alamat' => 'required|string|max:255',
            'kontak' => [
                'required',
                'numeric',
                'digits_between:11,13',
                Rule::unique('pasiens')->ignore($pasienId, 'id') // Pengecualian untuk kontak berdasarkan pasien ID
            ],
            'nik' => ['required', 'numeric', 'digits:16'],
            'email' => [
                'required',
                'string',
                'email',
                'lowercase',
                'max:255',
                Rule::unique('users')->ignore($userId) // Pengecualian berdasarkan user_id pasien
            ]
        ];
    }





    public function messages()
    {
        return [
            'nama.required' => 'Kolom nama tidak boleh kosong.',
            'nama.string' => 'Kolom nama harus berupa teks.',
            'nama.max' => 'Kolom nama tidak boleh lebih dari 255 karakter.',

            'tanggal_lahir.required' => 'Kolom tanggal lahir tidak boleh kosong.',
            'tanggal_lahir.date' => 'Kolom tanggal lahir harus berupa tanggal yang valid.',

            'tempat_lahir.required' => 'Kolom tempat lahir tidak boleh kosong.',
            'tempat_lahir.string' => 'Kolom tempat lahir harus berupa teks.',
            'tempat_lahir.max' => 'Kolom tempat lahir tidak boleh lebih dari 255 karakter.',

            'jk.required' => 'Kolom Jenis Kelamin wajib diisi.',
            'jk.in' => 'Kolom Jenis Kelamin hanya boleh berisi Laki-laki atau Perempuan.',

            'alamat.required' => 'Kolom alamat tidak boleh kosong.',
            'alamat.string' => 'Kolom alamat harus berupa teks.',
            'alamat.max' => 'Kolom alamat tidak boleh lebih dari 255 karakter.',

            'kontak.required' => 'Kolom kontak tidak boleh kosong.',
            'kontak.numeric' => 'Kolom kontak harus berupa angka.',
            'kontak.digits_between' => 'Kolom kontak harus terdiri dari 11 atau 13 digit.',
            'kontak.unique' => 'Kontak ini sudah digunakan oleh pengguna lain',

            'nik.required' => 'Kolom NIK tidak boleh kosong.',
            'nik.numeric' => 'Kolom NIK harus berupa angka.',
            'nik.digits' => 'Kolom NIK harus terdiri dari 16 digit.',

            'email.required' => 'Kolom email tidak boleh kosong.',
            'email.string' => 'Kolom email harus berupa teks.',
            'email.email' => 'Kolom email harus berformat valid (contoh: user@example.com).',
            'email.lowercase' => 'Email harus menggunakan huruf kecil.',
            'email.max' => 'Kolom email tidak boleh lebih dari 255 karakter.',
            'email.unique' => 'Email ini sudah digunakan oleh pengguna lain.',
        ];
    }
}
