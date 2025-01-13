<?php

namespace App\Casts;

use App\Models\Dokter;
use App\Models\Obat;
use App\Models\Pasien;
use App\Models\Pegawai;
use App\Models\Poli;
use App\Models\RekamMedik;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class Kode implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        if ($model instanceof RekamMedik) {
            return 'RM' . sprintf('%08d', $model->id);
        }

        if ($model instanceof Poli) {
            return 'PL' . sprintf('%08d', $model->id);
        }

        if ($model instanceof Obat) {
            return 'OBT' . sprintf('%08d', $model->id);
        }


        if ($model instanceof Pasien) {
            return 'PS' . sprintf('%08d', $model->id);
        }

        if ($model instanceof Dokter) {
            return 'D' . sprintf('%08d', $model->id);
        }


        if ($model instanceof Pegawai) {
            return 'P' . sprintf('%08d', $model->id);
        }


        return "";
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        return $value;
    }
}
