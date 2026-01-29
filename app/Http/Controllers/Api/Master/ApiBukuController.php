<?php

namespace App\Http\Controllers\Api\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Master\Buku\CreateBukuRequest;
use App\Http\Requests\Api\Master\Buku\GetBukuRequest;
use App\Services\api\master\BukuApiService;
use Illuminate\Http\Request;

class ApiBukuController extends Controller
{
    protected $bukuApiService;

    public function __construct(BukuApiService $bukuApiService)
    {
        $this->bukuApiService = $bukuApiService;
    }

    /**
     * Display a listing of the resource.
     */

    public function index(GetBukuRequest $request)
    {
        // Get request
        // $validatedRequest = $request->validated();
        $validatedRequest = $request->all();

        // Call service for get request
        $responseGetBuku = $this->bukuApiService->getBuku($validatedRequest);

        if (!$responseGetBuku['success']) {
            return response()->json([
                'success' => $responseGetBuku['success'] ?? false,
                'message' => $responseGetBuku['message'] ?? 'Ada error ketika ambil data buku',
                'data' => [],
            ], $responseGetBuku['statusCode'] ?? 500);
        }

        return response()->json([
            'success' => $responseGetBuku['success'] ?? false,
            'data' => $responseGetBuku['data']
        ], $responseGetBuku['statusCode'] ?? 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateBukuRequest $request)
    {
        // Get request
        // $validatedRequest = $request->validated();
        $validatedRequest = $request->all();

        // Call service for get request
        $responseCreateBuku = $this->bukuApiService->createBuku($validatedRequest);

        if (!$responseCreateBuku['success']) {
            return response()->json([
                'success' => $responseCreateBuku['success'] ?? false,
                'message' => $responseCreateBuku['message'] ?? 'Ada error ketika buat data buku',
                'data' => [],
            ], $responseCreateBuku['statusCode'] ?? 500);
        }

        return response()->json([
            'success' => $responseCreateBuku['success'] ?? false,
            'data' => $responseCreateBuku['data']
        ], $responseCreateBuku['statusCode'] ?? 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Call service for get request
        $dataBuku = $this->bukuApiService->findBukuById($id);

        if (!$dataBuku) {
            return response()->json([
                'success' => false,
                'message' => 'Data buku tidak ditemukan',
                'data' => [],
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $dataBuku
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CreateBukuRequest $request, string $id)
    {
        // Get request
        // $validatedRequest = $request->validated();
        $validatedRequest = $request->all();

        // Call service for get request
        $responseUpdateBuku = $this->bukuApiService->updateBuku($id, $validatedRequest);

        if (!$responseUpdateBuku['success']) {
            return response()->json([
                'success' => $responseUpdateBuku['success'] ?? false,
                'message' => $responseUpdateBuku['message'] ?? 'Ada error ketika update data buku',
                'data' => [],
            ], $responseUpdateBuku['statusCode'] ?? 500);
        }

        return response()->json([
            'success' => $responseUpdateBuku['success'] ?? false,
        ], $responseUpdateBuku['statusCode'] ?? 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Call service for get request
        $responseDeleteBuku = $this->bukuApiService->deleteBukuById($id);

        if (!$responseDeleteBuku['success']) {
            return response()->json([
                'success' => $responseDeleteBuku['success'] ?? false,
                'message' => $responseDeleteBuku['message'] ?? 'Ada error ketika hapus data buku',
                'data' => [],
            ], $responseDeleteBuku['statusCode'] ?? 500);
        }

        return response()->json([
            'success' => $responseDeleteBuku['success'] ?? false,
        ], $responseDeleteBuku['statusCode'] ?? 200);
    }
}
