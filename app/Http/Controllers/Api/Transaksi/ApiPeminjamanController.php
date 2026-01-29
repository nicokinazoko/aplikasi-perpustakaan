<?php

namespace App\Http\Controllers\Api\Transaksi;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Transaksi\Peminjaman\CreatePeminjamanRequest;
use App\Http\Requests\Api\Transaksi\Peminjaman\GetPeminjamanRequest;
use App\Services\api\transaksi\PeminjamanApiService;
use Illuminate\Http\Request;

class ApiPeminjamanController extends Controller
{
    protected $peminjamanService;
    public function __construct(PeminjamanApiService $peminjamanService)
    {
        $this->peminjamanService = $peminjamanService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(GetPeminjamanRequest $request)
    {
        // Get request
        $validatedRequest = $request->validated();

        // Call service for get request
        $responseGetPeminjaman = $this->peminjamanService->getPeminjaman($validatedRequest);

        if (!$responseGetPeminjaman['success']) {
            return response()->json([
                'success' => $responseGetPeminjaman['success'] ?? false,
                'message' => $responseGetPeminjaman['message'] ?? 'Ada error ketika ambil data peminjaman',
                'data' => [],
            ], $responseGetPeminjaman['statusCode'] ?? 500);
        }

        return response()->json([
            'success' => $responseGetPeminjaman['success'] ?? false,
            'data' => $responseGetPeminjaman['data']
        ], $responseGetPeminjaman['statusCode'] ?? 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreatePeminjamanRequest $request)
    {
        // Get request
        $validatedRequest = $request->validated();

        // Call service for get request
        $responseCreatePeminjaman = $this->peminjamanService->createPeminjaman($validatedRequest);

        if (!$responseCreatePeminjaman['success']) {
            return response()->json([
                'success' => $responseCreatePeminjaman['success'] ?? false,
                'message' => $responseCreatePeminjaman['message'] ?? 'Ada error ketika buat data peminjaman',
                'data' => [],
            ], $responseCreatePeminjaman['statusCode'] ?? 500);
        }

        return response()->json([
            'success' => $responseCreatePeminjaman['success'] ?? false,
        ], $responseCreatePeminjaman['statusCode'] ?? 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Call service for get request
        $dataPeminjaman = $this->peminjamanService->findPeminjamanById($id);

        if (!$dataPeminjaman) {
            return response()->json([
                'success' => false,
                'message' => 'Data peminjaman tidak ditemukan',
                'data' => [],
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $dataPeminjaman
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CreatePeminjamanRequest $request, string $id)
    {
        // Get request
        $validatedRequest = $request->validated();

        // Call service for get request
        $responseUpdatePeminjaman = $this->peminjamanService->updatePeminjaman($id, $validatedRequest);

        if (!$responseUpdatePeminjaman['success']) {
            return response()->json([
                'success' => $responseUpdatePeminjaman['success'] ?? false,
                'message' => $responseUpdatePeminjaman['message'] ?? 'Ada error ketika buat data peminjaman',
                'data' => [],
            ], $responseUpdatePeminjaman['statusCode'] ?? 500);
        }

        return response()->json([
            'success' => $responseUpdatePeminjaman['success'] ?? false,
            'data' => $responseUpdatePeminjaman['data']
        ], $responseUpdatePeminjaman['statusCode'] ?? 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Call service for get request
        $responseDeletePeminjaman = $this->peminjamanService->deletePeminjaman($id);

        if (!$responseDeletePeminjaman['success']) {
            return response()->json([
                'success' => $responseDeletePeminjaman['success'] ?? false,
                'message' => $responseDeletePeminjaman['message'] ?? 'Ada error ketika hapus data peminjaman',
                'data' => [],
            ], $responseDeletePeminjaman['statusCode'] ?? 500);
        }

        return response()->json([
            'success' => $responseDeletePeminjaman['success'] ?? false,
        ], $responseDeletePeminjaman['statusCode'] ?? 200);
    }
}
