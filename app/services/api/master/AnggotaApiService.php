<?php

namespace App\Services\api\master;

use App\Models\AnggotaModel;
use App\Models\PeminjamanModel;
use Illuminate\Support\Facades\Log;
use Exception;
use Throwable;

class AnggotaApiService
{
    /**
     *
     */
    public function getAnggota(array $filter)
    {
        try {
            // Get filter
            $keyword = $filter['filter'] ?? '';

            // Find data anggota
            $queryAnggota = AnggotaModel::where('deleted_at', null);

            if ($keyword) {
                $queryAnggota->where(function ($query) use ($keyword) {
                    $query->where('no_anggota', 'like', "%{$keyword}%")
                        ->orWhere('nama', 'like', "%{$keyword}%");
                });
            }

            // Get data anggota
            $listAnggota = $queryAnggota->get();

            return [
                'success' => true,
                'data' => $listAnggota,
                'statusCode' => 200,
            ];
        } catch (Throwable $th) {
            Log::error('Error in get anggota', [
                'error' => $th->getMessage(),
                'params' => $filter,
                'file' => $th->getFile(),
                'line' => $th->getLine(),
            ]);

            return [
                'success' => false,
                'message' => 'Ambil anggota gagal'
            ];
        }
    }

    public function findAnggotaById(string $idAnggota)
    {
        $anggota = AnggotaModel
            ::whereNull('deleted_at')
            ->where('id', $idAnggota)->first();

        return $anggota;
    }

    public function createAnggota(array $anggotaInput)
    {
        try {
            // create data anggota
            $createdAnggota = AnggotaModel::create($anggotaInput);

            return [
                'success' => true,
                'data' => $createdAnggota,
                'statusCode' => 200,
            ];
        } catch (Throwable $th) {
            Log::error('Error in create anggota', [
                'error' => $th->getMessage(),
                'params' => $anggotaInput,
                'file' => $th->getFile(),
                'line' => $th->getLine(),
            ]);

            return [
                'success' => false,
                'message' => 'Simpan anggota gagal'
            ];
        }
    }

    public function getLatestNomorAnggota()
    {
        try {
            // create data anggota
            $latestAnggotaNumber = AnggotaModel
                ::whereNull('deleted_at')
                ->latest('created_at')
                ->value('no_anggota');

            if (!$latestAnggotaNumber) {
                $latestAnggotaNumber = 1;
            } else {
                $latestAnggotaNumber++;
            }

            return [
                'success' => true,
                'data' => $latestAnggotaNumber,
                'statusCode' => 200,
            ];
        } catch (Throwable $th) {
            Log::error('Error in getLatestNomorAnggota', [
                'error' => $th->getMessage(),
                'file' => $th->getFile(),
                'line' => $th->getLine(),
            ]);

            return [
                'success' => false,
                'message' => 'ambil nomor anggota gagal'
            ];
        }
    }

    public function updateAnggota()
    {

    }

    public function deleteAnggota(string $idAnggota)
    {
        try {
            // Find anggota
            $anggota = $this->findAnggotaById($idAnggota);

            // If anggota not found, return validation message
            if (!$anggota) {
                return [
                    'success' => false,
                    'message' => 'Data anggota tidak ditemukan',
                    'statusCode' => 404,
                ];
            }

            // Check if there is anggota has peminjaman
            $isPeminjamanExist = PeminjamanModel::where('anggota_id', $idAnggota)->exists();

            if ($isPeminjamanExist) {
                return [
                    'success' => false,
                    'message' => 'Anggota masih meminjam barang',
                    'statusCode' => 422,
                ];
            }

            // Delete anggota
            $anggota->delete();

            return [
                'success' => true,
            ];

        } catch (Throwable $th) {
            Log::error('Error in deleteAnggota', [
                'error' => $th->getMessage(),
                'file' => $th->getFile(),
                'line' => $th->getLine(),
                'param' => $idAnggota,
            ]);

            return [
                'success' => false,
                'message' => 'hapus anggota gagal',
                'statusCode' => 500,
            ];
        }
    }
}
