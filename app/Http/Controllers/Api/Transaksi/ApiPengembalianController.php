<?php

namespace App\Http\Controllers\Api\Transaksi;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Transaksi\Pengembalian\CreatePengembalianRequest;
use App\Http\Requests\Api\Transaksi\Pengembalian\GetPengembalianRequest;
use App\Services\api\transaksi\PengembalianApiService;
use Illuminate\Http\Request;

class ApiPengembalianController extends Controller
{
    protected $pengembalianApiService;
    public function __construct(PengembalianApiService $pengembalianApiService)
    {

        $this->pengembalianApiService = $pengembalianApiService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(GetPengembalianRequest $request)
    {
        // Get request
        $validatedRequest = $request->validated();

        // Call service for get request
        $responseGetPengembalian = $this->pengembalianApiService->getPengembalian($validatedRequest);

        if (!$responseGetPengembalian['success']) {
            return response()->json([
                'success' => $responseGetPengembalian['success'] ?? false,
                'message' => $responseGetPengembalian['message'] ?? 'Ada error ketika ambil data pengembalian',
                'data' => [],
            ], $responseGetPengembalian['statusCode'] ?? 500);
        }

        return response()->json([
            'success' => $responseGetPengembalian['success'] ?? false,
            'data' => $responseGetPengembalian['data']
        ], $responseGetPengembalian['statusCode'] ?? 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreatePengembalianRequest $request)
    {
        // Get request
        $validatedRequest = $request->validated();

        // Call service for get request
        $responseCreatePengembalian = $this->pengembalianApiService->createPengembalian($validatedRequest);

        if (!$responseCreatePengembalian['success']) {
            return response()->json([
                'success' => $responseCreatePengembalian['success'] ?? false,
                'message' => $responseCreatePengembalian['message'] ?? 'Ada error ketika buat data pengembalian',
                'data' => [],
            ], $responseCreatePengembalian['statusCode'] ?? 500);
        }

        return response()->json([
            'success' => $responseCreatePengembalian['success'] ?? false,
            'data' => $responseCreatePengembalian['data']
        ], $responseCreatePengembalian['statusCode'] ?? 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Call service for get request
        $dataPengembalian = $this->pengembalianApiService->getPengembalianById($id);

        if (!$dataPengembalian) {
            return response()->json([
                'success' => false,
                'message' => 'Data pengembalian tidak ditemukan',
                'data' => [],
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $dataPengembalian
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CreatePengembalianRequest $request, string $id)
    {
        // Get request
        $validatedRequest = $request->validated();

        // Call service for get request
        $responseUpdatePengembalian = $this->pengembalianApiService->updatePengembalian($id, $validatedRequest);

        if (!$responseUpdatePengembalian['success']) {
            return response()->json([
                'success' => $responseUpdatePengembalian['success'] ?? false,
                'message' => $responseUpdatePengembalian['message'] ?? 'Ada error ketika update data pengembalian',
                'data' => [],
            ], $responseUpdatePengembalian['statusCode'] ?? 500);
        }

        return response()->json([
            'success' => $responseUpdatePengembalian['success'] ?? false,
        ], $responseUpdatePengembalian['statusCode'] ?? 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Call service for get request
        $responseDeletePengembalian = $this->pengembalianApiService->deletePengembalian($id);

        if (!$responseDeletePengembalian['success']) {
            return response()->json([
                'success' => $responseDeletePengembalian['success'] ?? false,
                'message' => $responseDeletePengembalian['message'] ?? 'Ada error ketika hapus data pengembalian',
                'data' => [],
            ], $responseDeletePengembalian['statusCode'] ?? 500);
        }

        return response()->json([
            'success' => $responseDeletePengembalian['success'] ?? false,
        ], $responseDeletePengembalian['statusCode'] ?? 200);
    }
}
