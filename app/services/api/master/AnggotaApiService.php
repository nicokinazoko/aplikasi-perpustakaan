<?php

namespace App\Services\api\master;

use App\Models\AnggotaModel;
use Illuminate\Support\Facades\Log;
use Exception;

class AnggotaApiService
{
    /**
     *
     */
    public function getAnggota(array $filter)
    {
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
    }
}
