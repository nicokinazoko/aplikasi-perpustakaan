<?php

namespace App\Services\api\transaksi;

use App\Models\AnggotaModel;
use App\Models\BukuModel;
use App\Models\PeminjamanDetailModel;
use App\Models\PeminjamanModel;
use App\Services\api\master\AnggotaApiService;
use App\Services\api\master\BukuApiService;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Support\Facades\DB;
use Throwable;

class PeminjamanApiService
{
    protected $anggotaApiService, $peminjamanDetailApiService, $bukuApiService;

    public function __construct(
        AnggotaApiService $anggotaApiService,
        PeminjamanDetailApiService $peminjamanDetailApiService,
        BukuApiService $bukuApiService,
    ) {
        $this->anggotaApiService = $anggotaApiService;
        $this->peminjamanDetailApiService = $peminjamanDetailApiService;
        $this->bukuApiService = $bukuApiService;
    }

    public function getPeminjaman(array $filter)
    {
        // Get filter
        $keyword = $filter['filter'] ?? '';

        // Find data peminjaman
        $queryPeminjaman = PeminjamanModel::select(
            'peminjamans.id',
            'peminjamans.anggota_id',
            'peminjamans.tanggal_pinjam',
            'anggotas.nama as nama_anggota',
            'anggotas.no_anggota as no_anggota',
            DB::raw('SUM(peminjaman_details.total_pinjam) as total_buku')
        )
            ->leftJoin('peminjaman_details', 'peminjaman_details.peminjaman_id', '=', 'peminjamans.id')
            ->leftJoin('anggotas', 'anggotas.id', '=', 'peminjamans.anggota_id')
            ->whereNull('peminjamans.deleted_at')
            ->groupBy(
                'peminjamans.id',
                'peminjamans.anggota_id',
                'peminjamans.tanggal_pinjam',
                'anggotas.nama',
                'anggotas.no_anggota'
            );


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
            // Get anggota by id
            $idAnggota = $inputPeminjaman['anggota_id'] ?? '';

            // Find anggota by id anggota
            $anggota = $this->anggotaApiService->findAnggotaById($idAnggota);

            // If anggota not found, return validation message
            if (!$anggota) {
                return [
                    'success' => false,
                    'message' => 'data anggota tidak ditemukan',
                    'statusCode' => 404,
                ];
            }

            // Get detail peminjaman
            $detailPeminjaman = $inputPeminjaman['detail_peminjaman'] ?? [];

            // If detail anggota not found, return validation message
            if (empty($detailPeminjaman)) {
                return [
                    'success' => false,
                    'message' => 'data detail peminjaman wajib diisi',
                    'statusCode' => 422,
                ];
            }

            // Check availability peminjaman
            $responseAvailabilityPeminjaman = $this->checkAvailablePeminjaman($detailPeminjaman);

            if (!$responseAvailabilityPeminjaman['success']) {
                return [
                    'success' => false,
                    'message' => $responseAvailabilityPeminjaman['message'] ?? 'stok buku tidak mencukupi',
                    'statusCode' => 422,
                ];
            }

            // Get total peminjaman
            $totalPeminjaman = array_reduce($detailPeminjaman, function ($carry, $item) {
                return $carry + ($item['total_pinjam'] ?? 0);
            }, 0);

            // Get max pinjam from anggota
            $maximumPinjam = $anggota['max_pinjam'] ?? 0;

            // Check if total peminjaman not past the max pinjam in anggota
            if ($totalPeminjaman > $maximumPinjam) {
                return [
                    'success' => false,
                    'message' => "Hanya bisa maksimal pinjam $maximumPinjam",
                    'statusCode' => 422,
                ];
            }

            // Start transaction
            DB::transaction(function () use ($detailPeminjaman, $inputPeminjaman, $totalPeminjaman, $anggota) {
                // Unset detail peminjaman
                unset($inputPeminjaman['detail_peminjaman']);

                // Create data peminjaman
                $dataPeminjaman = PeminjamanModel::create($inputPeminjaman);

                // Create detail peminjaman
                $dataDetailPeminjaman = $this->peminjamanDetailApiService->createPeminjamanDetail(
                    $dataPeminjaman['id'],
                    $detailPeminjaman
                );

                // Get sisa pinjaman
                $sisaPinjaman = $anggota['max_pinjam'] - $totalPeminjaman;

                // Decrement buku stock
                foreach ($detailPeminjaman as $item) {
                    $buku = BukuModel::find($item['buku_id']);
                    $buku->decrement('stok', $item['total_pinjam']);
                }

                // Update stok pinjaman anggota
                AnggotaModel
                    ::where('id', $anggota['id'])
                    ->update([
                        'max_pinjam' => $sisaPinjaman
                    ]);
            });

            return [
                'success' => true,
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
            // Get existing peminjaman
            $peminjaman = $this->findPeminjamanById($idPeminjaman);

            if (!$peminjaman) {
                return [
                    'success' => false,
                    'message' => 'data peminjaman tidak ditemukan',
                    'statusCode' => 404,
                ];
            }

            // Get anggota
            $anggota = $this->anggotaApiService->findAnggotaById($inputPeminjaman['anggota_id'] ?? $peminjaman->anggota_id);
            if (!$anggota) {
                return [
                    'success' => false,
                    'message' => 'data anggota tidak ditemukan',
                    'statusCode' => 404,
                ];
            }

            // Get new detail peminjaman
            $newDetail = $inputPeminjaman['detail_peminjaman'] ?? [];
            if (empty($newDetail)) {
                return [
                    'success' => false,
                    'message' => 'data detail peminjaman wajib diisi',
                    'statusCode' => 422,
                ];
            }

            // Start transaction
            DB::transaction(function () use ($peminjaman, $inputPeminjaman, $newDetail, $anggota) {
                // Get data peminjaman detail
                $listPeminjamanDetail = $this->peminjamanDetailApiService->findPeminjamanDetailByPeminjamanid($peminjaman->id);
                // 1. Restore old stock
                foreach ($listPeminjamanDetail as $oldItem) {
                    $buku = BukuModel::find($oldItem->buku_id);
                    if ($buku)
                        $buku->increment('stok', $oldItem->total_pinjam);
                }

                // 2. Check new stock availability
                foreach ($newDetail as $item) {
                    $result = $this->bukuApiService->checkStock($item['buku_id'], $item['total_pinjam']);
                    if (!$result['success']) {
                        throw new Exception($result['message']);
                    }
                }

                // 3. Update peminjaman data (except detail)
                unset($inputPeminjaman['detail_peminjaman']);
                $peminjaman->update($inputPeminjaman);

                // 4. Delete old detail & insert new
                $this->peminjamanDetailApiService->removePeminjamanDetailByIdPeminjam($peminjaman->id);
                $this->peminjamanDetailApiService->createPeminjamanDetail($peminjaman->id, $newDetail);

                // 5. Decrement stock for new detail
                foreach ($newDetail as $item) {
                    $buku = BukuModel::find($item['buku_id']);
                    if ($buku)
                        $buku->decrement('stok', $item['total_pinjam']);
                }

                // 6. Update anggota max_pinjam
                $totalPinjam = array_sum(array_column($newDetail, 'total_pinjam'));
                $sisaPinjam = $anggota['max_pinjam'] - $totalPinjam;
                AnggotaModel::where('id', $anggota['id'])->update(['max_pinjam' => $sisaPinjam]);
            });

            return [
                'success' => true,
                'statusCode' => 200,
            ];

        } catch (Throwable $th) {
            Log::error("updatePeminjaman Error", [
                'message' => $th->getMessage(),
                'file' => $th->getFile(),
                'line' => $th->getLine(),
                'input' => $inputPeminjaman,
            ]);

            return [
                'success' => false,
                'message' => 'Ada error ketika update peminjaman',
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

            DB::transaction(function () use ($peminjaman) {
                // Get data peminjaman detail
                $listPeminjamanDetail = $this->peminjamanDetailApiService->findPeminjamanDetailByPeminjamanid($peminjaman->id);

                // 1. Restore buku stock
                foreach ($listPeminjamanDetail as $item) {
                    $buku = BukuModel::find($item->buku_id);
                    if ($buku) {
                        $buku->increment('stok', $item->total_pinjam);
                    }
                }

                // 2. Restore anggota max_pinjam
                $anggota = AnggotaModel::find($peminjaman->anggota_id);

                if ($anggota) {
                    // Get total pinjam from peminjaman detail
                    $totalPinjam = PeminjamanDetailModel
                        ::whereNull('deleted_at')
                        ->where('peminjaman_id', $peminjaman->id)
                        ->sum('total_pinjam');

                    $anggota->increment('max_pinjam', $totalPinjam);
                }

                // 3. Delete peminjaman details
                $this->peminjamanDetailApiService->deletePeminjamanDetailByIdPeminjam($peminjaman->id);

                // 4. Delete peminjaman
                $peminjaman->delete();
            });

            return [
                'success' => true,
                'statusCode' => 200,
            ];

        } catch (Throwable $th) {
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

    public function checkAvailablePeminjaman(array $detailPeminjaman)
    {
        foreach ($detailPeminjaman as $item) {
            $result = $this->bukuApiService->checkStock($item['buku_id'], $item['total_pinjam']);
            if (!$result['success']) {
                return [
                    'success' => false,
                    'message' => $result['message'],
                    'statusCode' => 422,
                ];
            }
        }

        return [
            'success' => true,
        ];
    }
}
