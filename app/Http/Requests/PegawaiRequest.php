<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
        return [
            'nama' => ['required', 'string', 'max:100'],
            'jk' => ['required', 'in:Laki-Laki,Perempuan'],
            'email' => ['required', 'email'], 
            'bagian' => ['required', 'string'],
            'kontak' => ['required', 'numeric', 'digits_between:11,13'] 
        ];
    }

    public function messages(): array
    {
        return [
            'nama.required' => 'Kolom Nama wajib diisi.',
            'nama.string' => 'Nama harus berupa teks.',
            'nama.max' => 'Nama maksimal 100 karakter.',

            'jk.required' => 'Kolom Jenis Kelamin wajib diisi.',
            'jk.in' => 'Kolom Jenis Kelamin hanya boleh berisi Laki-laki atau Perempuan.',

            'email.required' => 'Kolom Email wajib diisi.',
            'email.email' => 'Email harus berformat valid (contoh: user@example.com).',

            'bagian.required' => 'Kolom Bagian wajib diisi.',
            'bagian.string' => 'Bagian harus berupa teks.',

            'kontak.required' => 'Kolom Kontak wajib diisi.',
            'kontak.numeric' => 'Kontak harus berupa angka.',
            'kontak.digits_between' => 'Kolom Kontak harus berisi antara 11 hingga 13 digit.',
        ];
    }

}
