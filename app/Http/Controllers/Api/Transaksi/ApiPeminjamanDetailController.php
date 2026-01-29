<?php

namespace App\Http\Controllers\Api\Transaksi;

use App\Http\Controllers\Controller;
use App\Services\api\transaksi\PeminjamanDetailApiService;
use Illuminate\Http\Request;

class ApiPeminjamanDetailController extends Controller
{
    protected $peminjamanDetailApiService;
    public function __construct(PeminjamanDetailApiService $peminjamanDetailApiService)
    {
        $this->peminjamanDetailApiService = $peminjamanDetailApiService;
    }

    public function populatePeminjamanDetail(string $id)
    {
        $dataPeminjaman = $this->peminjamanDetailApiService->populateDataPeminjamanDetailByPeminjamanId($id);

        return response()->json([
            'success' => true,
            'data' => $dataPeminjaman
        ], 200);
    }
}
