<?php

namespace App\Http\Controllers\Api\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Master\Anggota\CreateAnggotaRequest;
use App\Http\Requests\Api\Master\Anggota\GetAnggotaRequest;
use App\Services\api\master\AnggotaApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

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
        try {
            // Get request
            $validatedRequest = $request->validated();

            // Call service for get request
            $responseGetAnggota = $this->anggotaApiService->getAnggota($validatedRequest);

            if (!$responseGetAnggota['success']) {
                return response()->json([
                    'success' => $responseGetAnggota['success'] ?? false,
                    'message' => $responseGetAnggota['message'] ?? 'Ada error ketika ambil data anggota',
                    'data' => [],
                ], $responseGetAnggota['statusCode'] ?? 500);
            }

            return response()->json([
                'success' => $responseGetAnggota['success'] ?? false,
                'data' => $responseGetAnggota['data']
            ], $responseGetAnggota['statusCode'] ?? 200);
        } catch (Throwable $th) {
            Log::error('Error in get anggota', [
                'error' => $th->getMessage(),
                'params' => $request,
                'file' => $th->getFile(),
                'line' => $th->getLine(),
            ]);

            return response()->json([
                'success' => 'false',
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateAnggotaRequest $request)
    {
        try {
            // Validate input
            $validatedInput = $request->validated();

            // Call service for insert anggota
            $responseCreateAnggota = $this->anggotaApiService->createAnggota($validatedInput);

            if (!$responseCreateAnggota['success']) {
                return response()->json([
                    'success' => $responseCreateAnggota['success'] ?? false,
                    'message' => $responseCreateAnggota['message'] ?? 'Ada error ketika ambil data anggota',
                    'data' => [],
                ], $responseCreateAnggota['statusCode'] ?? 500);
            }

            return response()->json([
                'success' => $responseCreateAnggota['success'] ?? false,
            ], $responseCreateAnggota['statusCode'] ?? 200);

        } catch (Throwable $th) {
            Log::error('Error in create anggota', [
                'error' => $th->getMessage(),
                'params' => $request,
                'file' => $th->getFile(),
                'line' => $th->getLine(),
            ]);

            return response()->json([
                'success' => 'false',
                'message' => $th->getMessage(),
            ], 500);
        }
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

    public function getNomorAnggota()
    {
        try {
            // Call service for get nomor anggota
            $responseGetLatestAnggotaNumber = $this->anggotaApiService->createAnggota();

            if (!$responseGetLatestAnggotaNumber['success']) {
                return response()->json([
                    'success' => $responseGetLatestAnggotaNumber['success'] ?? false,
                    'message' => $responseGetLatestAnggotaNumber['message'] ?? 'Ada error ketika ambil data anggota',
                    'data' => [],
                ], $responseGetLatestAnggotaNumber['statusCode'] ?? 500);
            }

            return response()->json([
                'success' => $responseGetLatestAnggotaNumber['success'] ?? false,
            ], $responseGetLatestAnggotaNumber['statusCode'] ?? 200);

        } catch (Throwable $th) {
            Log::error('Error in get nomor anggota', [
                'error' => $th->getMessage(),
                'file' => $th->getFile(),
                'line' => $th->getLine(),
            ]);

            return response()->json([
                'success' => 'false',
                'message' => $th->getMessage(),
            ], 500);
        }
    }
}
