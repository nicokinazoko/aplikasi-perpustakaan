<?php

namespace App\Services\api\transaksi;

use App\Models\PeminjamanDetailModel;
use App\Models\PeminjamanModel;
use Illuminate\Support\Facades\Log;
use Exception;
use Throwable;

class PeminjamanDetailApiService
{
    public function deletePeminjamanDetailByIdPeminjam(string $idPeminjaman)
    {
        // Define query for peminjaman detail
        $deletedDataPeminjaman = PeminjamanDetailModel
            ::where('peminjaman_id', $idPeminjaman)
            ->delete();

        if ($deletedDataPeminjaman === 0) {
            return [
                'success' => false,
                'message' => 'data peminjaman detail tidak ditemukan',
                'statusCode' => 404
            ];
        }

        return [
            'success' => true,
            'data' => $deletedDataPeminjaman,
            'statusCode' => 200,
        ];
    }

    public function removePeminjamanDetailByIdPeminjam(string $idPeminjaman)
    {
        // Define query for peminjaman detail
        $deletedDataPeminjaman = PeminjamanDetailModel
            ::where('peminjaman_id', $idPeminjaman)
            ->forceDelete();

        if ($deletedDataPeminjaman === 0) {
            return [
                'success' => false,
                'message' => 'data peminjaman detail tidak ditemukan',
                'statusCode' => 404
            ];
        }

        return [
            'success' => true,
            'data' => $deletedDataPeminjaman,
            'statusCode' => 200,
        ];
    }

    public function createPeminjamanDetail(string $idPeminjaman, array $inputPeminjamanDetail)
    {
        $data = [];

        foreach ($inputPeminjamanDetail as $detail) {
            $detail['peminjaman_id'] = $idPeminjaman;
            $data[] = PeminjamanDetailModel::create($detail);
        }

        return $data;
    }

    public function findPeminjamanDetailByPeminjamanid(string $idPeminjaman)
    {
        $dataPeminjamanDetail = PeminjamanDetailModel
            ::whereNull('deleted_at')
            ->where('peminjaman_id', $idPeminjaman)
            ->get();

        return $dataPeminjamanDetail;
    }

    public function populateDataPeminjamanDetailByPeminjamanId(string $idPeminjaman)
    {
        $dataPeminjamanDetail = PeminjamanDetailModel
            ::whereNull('peminjaman_details.deleted_at')
            ->where('peminjaman_id', $idPeminjaman)
            ->leftJoin('peminjamans', 'peminjamans.id', '=', 'peminjaman_details.peminjaman_id')
            ->leftJoin('bukus', 'bukus.id', 'peminjaman_details.buku_id')
            ->get();

        return $dataPeminjamanDetail;
    }
}
