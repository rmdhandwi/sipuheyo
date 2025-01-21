<?php

namespace App\Http\Requests;

use App\Models\Poli;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PoliRequest extends FormRequest
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
        $poliId = request()->route('id'); // ID poli yang sedang diedit (jika ada)

        return [
            'nama' => ['required', 'string', 'max:255'],
            'penyakit' => ['required', 'string', 'max:255'],
            'dokter_id' => [
                'required',
                'exists:dokters,id',
                Rule::unique('polis')->where(function ($query) use ($poliId) {
                    return $query->where('id', '!=', $poliId);
                }),
            ],
            'pegawai_id' => [
                'required',
                'exists:pegawais,id',
            ],
            'keterangan' => ['required', 'string', 'max:255'],
        ];
    }



    public function messages(): array
    {
        return [
            'nama.required' => 'Kolom nama tidak boleh kosong.',
            'penyakit.required' => 'Kolom penyakit tidak boleh kosong.',
            'dokter_id.required' => 'Kolom dokter tidak boleh kosong.',
            'dokter_id.exists' => 'Dokter yang dipilih tidak valid.',
            'dokter_id.unique' => 'Dokter ini sudah digunakan.',
            'pegawai_id.required' => 'Kolom pegawai tidak boleh kosong.',
            'pegawai_id.exists' => 'Pegawai yang dipilih tidak valid.',
            'keterangan.required' => 'Kolom keterangan tidak boleh kosong.',
        ];
    }
}
