<?php

namespace App\Http\Controllers\Api\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Master\Anggota\GetAnggotaRequest;
use App\Services\api\master\AnggotaApiService;
use Illuminate\Http\Request;

class ApiAnggotaController extends Controller
{
    protected $anggotaApiService;

    public function __construct(AnggotaApiService $anggotaApiService)
    {
        $this->anggotaApiService = $anggotaApiService;
    }


    /**
     * Display a listing of the resource.
     */
    public function index(GetAnggotaRequest $request)
    {
        // Get request
        $validatedRequest = $request->validated();

        // Call service for get request
        $responseGetAnggota = $this->anggotaApiService->getAnggota($validatedRequest);

        if (!$responseGetAnggota['success']) {
            return response()->json([
                'success' => $responseGetAnggota['success'] ?? false,
                'message' => $responseGetAnggota['message'] ?? 'Ada error ketika ambil data anggota',
            ], $responseGetAnggota['statusCode'] ?? 500);
        }

        return response()->json([
            'success' => $responseGetAnggota['success'] ?? false,
            'data' => $responseGetAnggota['data']
        ], $responseGetAnggota['statusCode'] ?? 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
