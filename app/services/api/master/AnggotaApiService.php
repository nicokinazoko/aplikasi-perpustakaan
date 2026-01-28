<?php

namespace App\Services\api\master;

use Illuminate\Support\Facades\Log;
use Exception;

class AnggotaApiService
{
    /**
     *
     */
    public function getAnggota(array $filter)
    {
        // Get data anggota

        return [
            'success' => true,
            'data' => [],
            'statusCode' => 200,
        ];
    }
}
