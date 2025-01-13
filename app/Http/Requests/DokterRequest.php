<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DokterRequest extends FormRequest
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
            'nid' => ['required', 'numeric', 'digits_between:12,12'], 
            'kontak' => ['required', 'numeric', 'digits_between:11,13'], 
            'nama' => ['required', 'string', 'max:255'], 
            'email' => ['required', 'email', 'max:255'], 
            'jk' => ['required', 'in:Laki-Laki,Perempuan'], 
            'spesialis' => ['required', 'string'], 
        ];
    }

    public function messages(): array
    {
        return [
            'nid.required' => 'Kolom NID wajib diisi.',
            'nid.numeric' => 'Kolom NID harus berupa angka.',
            'nid.digits_between' => 'Kolom NID harus berisi 12 digit.',

            'kontak.required' => 'Kolom Kontak wajib diisi.',
            'kontak.numeric' => 'Kolom Kontak harus berupa angka.',
            'kontak.digits_between' => 'Kolom Kontak harus berisi antara 11 hingga 13 digit.',

            'nama.required' => 'Kolom Nama wajib diisi.',
            'nama.string' => 'Kolom Nama harus berupa teks.',
            'nama.max' => 'Kolom Nama tidak boleh lebih dari 255 karakter.',

            'email.required' => 'Kolom Email wajib diisi.',
            'email.email' => 'Kolom Email harus berisi format email yang valid (contoh: user@example.com).',
            'email.max' => 'Kolom Email tidak boleh lebih dari 255 karakter.',

            'jk.required' => 'Kolom Jenis Kelamin wajib diisi.',
            'jk.in' => 'Kolom Jenis Kelamin hanya boleh berisi Laki-laki atau Perempuan.',

            'spesialis.required' => 'Kolom Spesialis wajib diisi.',
            'spesialis.string' => 'Kolom Spesialis harus berupa teks.',
        ];
    }

}
