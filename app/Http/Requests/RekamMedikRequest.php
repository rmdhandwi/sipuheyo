<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RekamMedikRequest extends FormRequest
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
            'poli_id' => [
                'required',
                'exists:polis,id',
            ],
            'pasien_id' => [
                'required',
                'exists:pasiens,id',
            ],
            'dokter_id' => [
                'required',
                'exists:dokters,id',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'poli_id.required' => 'Poli tidak boleh kosong.',
            'poli_id.exists' => 'Poli yang dipilih tidak valid.',
            'pasien_id.required' => 'Pasien tidak boleh kosong.',
            'pasien_id.exists' => 'Pasien yang dipilih tidak valid.',
            'dokter_id.required' => 'Dokter tidak boleh kosong.',
            'dokter_id.exists' => 'Dokter yang dipilih tidak valid.',
        ];
    }
}
