<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DokterRekamMedikRequest extends FormRequest
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
            'resep' => 'required|array',
            'penanganan' => 'required|array',
            'keluhan' => 'required|array',
        ];
    }

    public function messages(): array
    {
        return [
            'kondisi.required' => 'Kondisi wajib diisi.',
            'penanganan.required' => 'Kondisi wajib diisi.',
            'resep.required' => 'Kondisi wajib diisi.',
            'keluhan.required' => 'Keluhan wajib diisi.',
        ];
    }
}
