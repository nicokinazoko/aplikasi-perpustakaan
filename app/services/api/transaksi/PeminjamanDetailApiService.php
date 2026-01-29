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
        // Merge id peminjaman into detail data
        $inputPeminjamanDetailMerged = array_merge($inputPeminjamanDetail, [
            'id_peminjaman' => $idPeminjaman,
        ]);

        // Craete data peminjaman detail
        $dataPeminjamanDetail = PeminjamanDetailModel::create($inputPeminjamanDetailMerged);

        return $dataPeminjamanDetail;
    }

    public function findPeminjamanDetailByPeminjamanid(string $idPeminjaman)
    {
        $dataPeminjamanDetail = PeminjamanDetailModel
            ::whereNull('deleted_at')
            ->where('peminjaman_id', $idPeminjaman)
            ->get;

        return $dataPeminjamanDetail;
    }
}
