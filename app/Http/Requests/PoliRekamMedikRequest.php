<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PoliRekamMedikRequest extends FormRequest
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
            'kondisi' => 'required|array',
            'keluhan' => 'required|array',
            'hasil_lab' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:10240', // Validasi gambar
        ];
    }

    public function messages(): array
    {
        return [
            'kondisi.required' => 'Kondisi wajib diisi.',
            'keluhan.required' => 'Keluhan wajib diisi.',
            'hasil_lab.image' => 'File harus berupa gambar.',
            'hasil_lab.mimes' => 'File harus memiliki ekstensi JPG, JPEG, PNG, atau GIF.',
            'hasil_lab.max' => 'Ukuran file tidak boleh lebih dari 10MB.',
        ];
    }
}
