<?php

namespace App\Services\api\master;

use App\Models\AnggotaModel;
use App\Models\BukuModel;
use Illuminate\Support\Facades\Log;
use Exception;
use Throwable;

class BukuApiService
{
    public function getBuku(array $filter)
    {
        // Get filter
        $keyword = $filter['filter'] ?? '';

        // Find data anggota
        $queryBuku = BukuModel::whereNull('deleted_at');

        if ($keyword) {
            $queryBuku->where(function ($query) use ($keyword) {
                $query->where('judul_buku', 'like', "%{$keyword}%")
                    ->orWhere('penerbit', 'like', "%{$keyword}%");
            });
        }

        // Get data anggota
        $listBuku = $queryBuku->get();

        return [
            'success' => true,
            'data' => $listBuku,
            'statusCode' => 200,
        ];
    }

    public function createBuku(array $inputBuku)
    {
        try {
            $createdBuku = BukuModel::create($inputBuku);

            return [
                'success' => true,
                'data' => $createdBuku,
                'statusCode' => 200,
            ];
        } catch (Throwable $th) {
            // Log error
            Log::error("createBuku Error", [
                'message' => $th->getMessage(),
                'file' => $th->getFile(),
                'line' => $th->getLine(),
                'input' => $inputBuku,
            ]);

            return [
                'success' => false,
                'message' => 'Ada error ketika create buku',
                'statusCode' => 500,
            ];
        }
    }

    public function updateBuku(string $idBuku, array $inputBuku)
    {
        try {
            // Find buku using id buku
            $buku = $this->findBukuById($idBuku);

            // If buku already exist, return validation message
            if (!$buku) {
                return [
                    'success' => false,
                    'message' => 'Data buku tidak ditemukan',
                    'statusCode' => 404,
                ];
            }

            // update data using input
            $buku->update($inputBuku);

            return [
                'success' => true,
                'data' => $buku->fresh(),
                'statusCode' => 200,
            ];
        } catch (Throwable $th) {
            // Log error
            Log::error("getDataKontrakByTanggalPesanan Error", [
                'message' => $th->getMessage(),
                'file' => $th->getFile(),
                'line' => $th->getLine(),
                'buku_id' => $idBuku,
                'input' => $inputBuku,
            ]);

            return [
                'success' => false,
                'message' => 'Ada error ketika update buku',
                'statusCode' => 500,
            ];
        }
    }

    public function findBukuById(string $idBuku)
    {
        // Find buku using id buku
        $buku = BukuModel
            ::whereNull('deleted_at')
            ->where('id', $idBuku)
            ->first();

        return $buku;
    }

    public function deleteBukuById(string $idBuku)
    {
        try {
            // Find buku using id buku
            $buku = $this->findBukuById($idBuku);

            // If buku already exist, return validation message
            if (!$buku) {
                return [
                    'success' => false,
                    'message' => 'Data buku tidak ditemukan',
                    'statusCode' => 404,
                ];
            }

            // update data using input
            $buku->delete();

            return [
                'success' => true,
                'data' => $idBuku,
                'statusCode' => 200,
            ];
        } catch (Throwable $th) {
            Log::error('deleteBukuById Error', [
                'buku_id' => $idBuku,
                'message' => $th->getMessage(),
                'file' => $th->getFile(),
                'line' => $th->getLine(),
            ]);

            return [
                'success' => false,
                'message' => 'Ada error ketika hapus buku',
                'statusCode' => 500,
            ];
        }
    }

    public function checkStock(string $bukuId, int $jumlah)
    {
        $buku = BukuModel::find($bukuId);

        if (!$buku) {
            return [
                'success' => false,
                'message' => "Buku ID {$bukuId} tidak ditemukan",
            ];
        }

        if ($jumlah > $buku->stok) {
            return [
                'success' => false,
                'message' => "Stok buku '{$buku->judul_buku}' tidak mencukupi (tersisa: {$buku->stok})",
            ];
        }

        return ['success' => true, 'buku' => $buku];
    }
}
