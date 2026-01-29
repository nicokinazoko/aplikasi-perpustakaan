<?php

namespace App\Services\api\transaksi;

use App\Models\PeminjamanModel;
use Illuminate\Support\Facades\Log;
use Exception;
use Throwable;

class PeminjamanApiService
{
    public function getPeminjaman(array $filter)
    {
        // Get filter
        $keyword = $filter['filter'] ?? '';

        // Find data peminjaman
        $queryPeminjaman = PeminjamanModel
            ::leftJoin('peminjaman_details', 'peminjaman_details.peminjaman_id', '=', 'peminjamans.id')
            ->leftJoin('anggotas', 'anggotas.id', '=', 'peminjamans.anggota_id')
            ->leftJoin('bukus', 'bukus.id', '=', 'peminjaman_details.buku_id')
            ->whereNull('peminjamans.deleted_at');

        if ($keyword) {
            $queryPeminjaman->where(function ($query) use ($keyword) {
                $query->where('anggotas.nama', 'like', "%{$keyword}%")
                    ->orWhere('anggotas.no_anggota', 'like', "%{$keyword}%");
            });
        }

        // Get data peminjaman
        $listPeminjaman = $queryPeminjaman->get();

        return [
            'success' => true,
            'data' => $listPeminjaman,
            'statusCode' => 200,
        ];
    }

    public function findPeminjamanById(string $idPeminjaman)
    {
        $peminjaman = PeminjamanModel
            ::whereNull('deleted_at')
            ->where('id', $idPeminjaman)
            ->first();

        return $peminjaman;
    }

    public function createPeminjaman(array $inputPeminjaman)
    {
        try {
            // TODO: Find data anggota id

            return [
                'success' => true,
                // 'data' => $listPeminjaman,
                'statusCode' => 200,
            ];
        } catch (Throwable $th) {
            // Log error
            Log::error("createPeminjaman Error", [
                'message' => $th->getMessage(),
                'file' => $th->getFile(),
                'line' => $th->getLine(),
                'input' => $inputPeminjaman,
            ]);

            return [
                'success' => false,
                'message' => 'Ada error ketika create peminjaman',
                'statusCode' => 500,
            ];
        }
    }

    public function updatePeminjaman(string $idPeminjaman, array $inputPeminjaman)
    {
        try {
            // Get data peminjaman
            $peminjaman = $this->findPeminjamanById($idPeminjaman);

            // if peminjaman not found, then return validation message
            if (!$peminjaman) {
                return [
                    'success' => false,
                    'message' => 'data peminjaman tidak ditemukan',
                    'statusCode' => 404,
                ];
            }

            // TODO: Find data anggota id


            return [
                'success' => true,
                // 'data' => $listPeminjaman,
                'statusCode' => 200,
            ];
        } catch (Throwable $th) {
            // Log error
            Log::error("createPeminjaman Error", [
                'message' => $th->getMessage(),
                'file' => $th->getFile(),
                'line' => $th->getLine(),
                'input' => $inputPeminjaman,
            ]);

            return [
                'success' => false,
                'message' => 'Ada error ketika create peminjaman',
                'statusCode' => 500,
            ];
        }
    }

    public function checkSisaPinjaman(string $idAnggota, string $idPeminjaman)
    {
        // Get total sisa pinjaman

    }

    public function deletePeminjaman(string $idPeminjaman)
    {
        try {
            // Find peminjaman
            $peminjaman = $this->findPeminjamanById($idPeminjaman);

            if (!$peminjaman) {
                return [
                    'success' => false,
                    'message' => 'data peminjaman tidak ditemukan',
                    'statusCode' => 404,
                ];
            }

            $peminjaman->delete();

            return [
                'success' => true,
                'statusCode' => 200
            ];

        } catch (Throwable $th) {
            // Log error
            Log::error("deletePeminjaman Error", [
                'message' => $th->getMessage(),
                'file' => $th->getFile(),
                'line' => $th->getLine(),
                'input' => $idPeminjaman,
            ]);

            return [
                'success' => false,
                'message' => 'Ada error ketika delete peminjaman',
                'statusCode' => 500,
            ];
        }
    }
}
