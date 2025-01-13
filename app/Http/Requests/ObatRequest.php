<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ObatRequest extends FormRequest
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
            'nama' => ['required', 'string', 'max:255'], // Nama wajib diisi, berupa teks, maksimal 255 karakter.
            'dosis' => ['required', 'string', 'max:255'], // Dosis wajib diisi, berupa teks, maksimal 255 karakter.
            'merek' => ['required', 'string', 'max:255'], // Merek wajib diisi, berupa teks, maksimal 255 karakter.
            'kemasan' => ['required', 'string', 'max:255'], // Kemasan wajib diisi, berupa teks, maksimal 255 karakter.
        ];
    }

    public function messages(): array
    {
        return [
            'nama.required' => 'Kolom Nama wajib diisi.',
            'nama.string' => 'Kolom Nama harus berupa teks.',
            'nama.max' => 'Kolom Nama tidak boleh lebih dari 255 karakter.',

            'dosis.required' => 'Kolom Dosis wajib diisi.',
            'dosis.string' => 'Kolom Dosis harus berupa teks.',
            'dosis.max' => 'Kolom Dosis tidak boleh lebih dari 255 karakter.',

            'merek.required' => 'Kolom Merek wajib diisi.',
            'merek.string' => 'Kolom Merek harus berupa teks.',
            'merek.max' => 'Kolom Merek tidak boleh lebih dari 255 karakter.',

            'kemasan.required' => 'Kolom Kemasan wajib diisi.',
            'kemasan.string' => 'Kolom Kemasan harus berupa teks.',
            'kemasan.max' => 'Kolom Kemasan tidak boleh lebih dari 255 karakter.',
        ];
    }

}
