<?php

namespace App\Http\Controllers;

use App\Models\RekamMedik;
use App\Services\PoliService;
use Illuminate\Http\Request;

class RekamMedikController extends Controller
{
    public function index(Request $request, PoliService $poliService)
    {
        $filters = [
            'poli' => $request->query('poli'),
            'bulan' => $request->query('bulan'),
        ];

        $query = RekamMedik::with(['dokter', 'poli', 'pasien'])
            ->orderBy('tanggal', 'DESC');

        if (!empty($filters['poli'])) {
            $query->where('poli_id', $filters['poli']);
        }

        if (!empty($filters['bulan'])) {
            $query->whereMonth('tanggal', $filters['bulan']);
        }

        $data = $query->paginate(10);

        return inertia('Admin/RekamMedikPage', [
            'data' => $data,
            'polis' => $poliService->data(),
            'filters' => $filters,
        ]);
    }
}
