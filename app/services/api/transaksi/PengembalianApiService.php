<?php

namespace App\Services\api\transaksi;

use App\Models\AnggotaModel;
use App\Models\BukuModel;
use App\Models\PeminjamanModel;
use App\Models\PengembalianModel;
use App\Services\api\master\AnggotaApiService;
use App\Services\api\master\BukuApiService;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Support\Facades\DB;
use Throwable;

class PengembalianApiService
{
    protected $peminjamanApiService, $peminjamanDetailApiService;
    public function __construct(PeminjamanApiService $peminjamanApiService, PeminjamanDetailApiService $peminjamanDetailApiService)
    {
        $this->peminjamanApiService = $peminjamanApiService;
        $this->peminjamanDetailApiService = $peminjamanDetailApiService;
    }

    public function getPengembalian(array $filter)
    {
        // Get filter
        $keyword = $filter['filter'] ?? '';

        // Find data peminjaman
        $queryPengembalian = PengembalianModel
            ::leftJoin('peminjamans', 'peminjamans.id', '=', 'pengembalians.peminjaman_id')
            ->leftJoin('peminjaman_details', 'peminjaman_details.peminjaman_id', '=', 'peminjamans.id')
            ->leftJoin('anggotas', 'anggotas.id', '=', 'peminjamans.anggota_id')
            ->leftJoin('bukus', 'bukus.id', '=', 'peminjaman_details.buku_id')
            ->whereNull('pengembalians.deleted_at');

        if ($keyword) {
            $queryPengembalian->where(function ($query) use ($keyword) {
                $query->where('anggotas.nama', 'like', "%{$keyword}%")
                    ->orWhere('anggotas.no_anggota', 'like', "%{$keyword}%");
            });
        }

        // Get data peminjaman
        $listPeminjaman = $queryPengembalian->get();

        return [
            'success' => true,
            'data' => $listPeminjaman,
            'statusCode' => 200,
        ];
    }

    public function getPengembalianById(string $idPengembalian)
    {
        $pengembalian = PengembalianModel
            ::where('id', $idPengembalian)
            ->whereNull('deleted_at')
            ->first();

        return $pengembalian;
    }

    public function createPengembalian(array $inputPengembalian)
    {
        try {
            // Get id peminjaman
            $idPeminjaman = $inputPengembalian['peminjaman_id'] ?? '';

            // Get data peminjaman by id
            $peminjaman = $this->peminjamanApiService->findPeminjamanById($idPeminjaman);

            // If peminjaman not found, return validation message
            if (!$peminjaman) {
                return [
                    'success' => false,
                    'message' => 'data peminjaman tidak ditemukan',
                    'statusCode' => 404,
                ];
            }

            // Check if pengembalian already exists
            $existingPengembalian = PengembalianModel::where('peminjaman_id', $peminjaman->id)
                ->whereNull('deleted_at')
                ->first();

            if ($existingPengembalian) {
                return [
                    'success' => false,
                    'message' => 'Peminjaman sudah dikembalikan sebelumnya',
                    'statusCode' => 409, // Conflict
                ];
            }

            // Start transaction
            $pengembalian = DB::transaction(function () use ($peminjaman, $inputPengembalian) {
                // 1. Create pengembalian record
                $newPengembalian = PengembalianModel::create([
                    'peminjaman_id' => $peminjaman->id,
                    'tanggal_kembali' => $inputPengembalian['tanggal_kembali'] ?? now(),
                ]);

                // Get detail buku peminjaman
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
                    $totalPinjam = collect($listPeminjamanDetail)->sum('total_pinjam');
                    $anggota->increment('max_pinjam', $totalPinjam);
                }

                return $newPengembalian;
            });

            return [
                'success' => true,
            ];

        } catch (Throwable $th) {
            Log::error("createPengembalian Error", [
                'message' => $th->getMessage(),
                'file' => $th->getFile(),
                'line' => $th->getLine(),
                'input' => $inputPengembalian,
            ]);

            return [
                'success' => false,
                'message' => 'Ada error ketika create pengemmbalian',
                'statusCode' => 500,
            ];
        }
    }

    public function updatePengembalian(string $idPengembalian, array $inputPengembalian)
    {
        try {

        } catch (Throwable $th) {
            Log::error("createPengembalian Error", [
                'message' => $th->getMessage(),
                'file' => $th->getFile(),
                'line' => $th->getLine(),
                'input' => $inputPengembalian,
                'id' => $idPengembalian,
            ]);

            return [
                'success' => false,
                'message' => 'Ada error ketika create pengemmbalian',
                'statusCode' => 500,
            ];
        }
    }

    public function deletePengembalian(string $idPengembalian)
    {
        try {
            // Get pengembalian
            $pengembalian = $this->getPengembalianById($idPengembalian);

            if (!$pengembalian) {
                return [
                    'success' => false,
                    'message' => 'Data pengembalian tidak ditemukan',
                    'statusCode' => 404,
                ];
            }

            // Get id peminjaman
            $idPeminjaman = $pengembalian['peminjaman_id'] ?? null;

            // Get data peminjaman
            $peminjaman = $this->peminjamanApiService->findPeminjamanById($idPeminjaman);

            if (!$peminjaman) {
                return [
                    'success' => false,
                    'message' => 'Data peminjaman tidak ditemukan',
                    'statusCode' => 404,
                ];
            }

            // Start transaction
            DB::transaction(function () use ($pengembalian, $peminjaman) {
                // 1. Restore buku stock
                $listPeminjamanDetail = $this->peminjamanDetailApiService
                    ->findPeminjamanDetailByPeminjamanid($peminjaman->id);

                foreach ($listPeminjamanDetail as $item) {
                    $buku = BukuModel::find($item->buku_id);
                    if ($buku) {
                        $buku->increment('stok', $item->total_pinjam);
                    }
                }

                // 2. Restore anggota max_pinjam
                $anggota = AnggotaModel::find($peminjaman->anggota_id);
                if ($anggota) {
                    $totalPinjam = collect($listPeminjamanDetail)->sum('total_pinjam');
                    $anggota->increment('max_pinjam', $totalPinjam);
                }

                // 3. Delete pengembalian record
                $pengembalian->delete();
            });

            return [
                'success' => true,
                'message' => 'Pengembalian berhasil dihapus',
                'statusCode' => 200,
            ];

        } catch (Throwable $th) {
            Log::error("deletePengembalian Error", [
                'message' => $th->getMessage(),
                'file' => $th->getFile(),
                'line' => $th->getLine(),
                'idPengembalian' => $idPengembalian,
            ]);

            return [
                'success' => false,
                'message' => 'Terjadi error saat menghapus pengembalian',
                'statusCode' => 500,
            ];
        }
    }

}
