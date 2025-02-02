<?php

namespace App\Http\Requests;

use App\Models\Pasien;
use App\Models\User;
use Illuminate\Validation\Rules\Password;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
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

        return [
            'nama' => 'required|string|max:255',
            'rekammedik' => [
                'required',
                'string',
                'max:255',
                'regex:/^\S*$/', 
                Rule::unique('pasiens')->ignore($pasienId, 'id'),
            ],
            'nik' => [
                'required',
                'numeric',
                'digits:16',
                Rule::unique('pasiens')->ignore($pasienId, 'id'),
            ],
            'tanggal_lahir' => 'required|date',
            'tempat_lahir' => 'required|string|max:255',
            'jk' => ['required', 'in:Laki-Laki,Perempuan'],
            'layanan' => ['required', 'in:BPJS,UMUM'],
            'alamat' => 'required|string|max:255',
            'kontak' => [
                'required',
                'numeric',
                'digits_between:11,13',
                Rule::unique('pasiens')->ignore($pasienId, 'id'),
            ],
            'email' => [
                'nullable',
                'string',
                'email',
                'lowercase',
                'max:255',
                function ($attribute, $value, $fail) use ($userId, $pasienId) {
                    $existsInUsers = DB::table('users')
                        ->where('email', $value)
                        ->when($userId, function ($query, $userId) {
                            $query->where('id', '!=', $userId);
                        })
                        ->exists();

                    $existsInPasiens = DB::table('pasiens')
                        ->where('email', $value)
                        ->when($pasienId, function ($query, $pasienId) {
                            $query->where('id', '!=', $pasienId);
                        })
                        ->exists();

                    if ($existsInUsers || $existsInPasiens) {
                        $fail(__('Email ini sudah digunakan oleh pengguna lain.'));
                    }
                }
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'nama.required' => 'Kolom nama tidak boleh kosong.',
            'nama.string' => 'Kolom nama harus berupa teks.',
            'nama.max' => 'Kolom nama tidak boleh lebih dari 255 karakter.',

            'rekammedik.required' => 'Kolom kode rekam medik tidak boleh kosong.',
            'rekammedik.string' => 'Kolom kode rekam medik harus berupa teks.',
            'rekammedik.max' => 'Kolom kode rekam medik tidak boleh lebih dari 255 karakter.',
            'rekammedik.unique' => 'Kode rekam medik ini sudah digunakan.',
            'rekammedik.regex' => 'Kode rekam medik tidak boleh mengandung spasi.',

            'nik.required' => 'Kolom NIK tidak boleh kosong.',
            'nik.numeric' => 'Kolom NIK harus berupa angka.',
            'nik.digits' => 'Kolom NIK harus terdiri dari 16 digit.',
            'nik.unique' => 'NIK ini sudah digunakan oleh pengguna lain.',

            'tanggal_lahir.required' => 'Kolom tanggal lahir tidak boleh kosong.',
            'tanggal_lahir.date' => 'Kolom tanggal lahir harus berupa tanggal yang valid.',

            'tempat_lahir.required' => 'Kolom tempat lahir tidak boleh kosong.',
            'tempat_lahir.string' => 'Kolom tempat lahir harus berupa teks.',
            'tempat_lahir.max' => 'Kolom tempat lahir tidak boleh lebih dari 255 karakter.',

            'jk.required' => 'Kolom jenis kelamin wajib diisi.',
            'jk.in' => 'Kolom jenis kelamin hanya boleh berisi Laki-Laki atau Perempuan.',

            'layanan.required' => 'Kolom layanan wajib diisi.',
            'layanan.in' => 'Kolom layanan hanya boleh berisi BPJS atau UMUM.',

            'alamat.required' => 'Kolom alamat tidak boleh kosong.',
            'alamat.string' => 'Kolom alamat harus berupa teks.',
            'alamat.max' => 'Kolom alamat tidak boleh lebih dari 255 karakter.',

            'kontak.required' => 'Kolom kontak tidak boleh kosong.',
            'kontak.numeric' => 'Kolom kontak harus berupa angka.',
            'kontak.digits_between' => 'Kolom kontak harus terdiri dari 11 hingga 13 digit.',
            'kontak.unique' => 'Kontak ini sudah digunakan oleh pengguna lain.',

            'email.string' => 'Kolom email harus berupa teks.',
            'email.email' => 'Kolom email harus berformat valid (contoh: user@example.com).',
            'email.lowercase' => 'Email harus menggunakan huruf kecil.',
            'email.max' => 'Kolom email tidak boleh lebih dari 255 karakter.',
        ];
    }
}
