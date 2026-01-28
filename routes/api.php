<?php

use App\Http\Controllers\Api\Master\AnggotaController;
use App\Http\Controllers\Api\Master\ApiAnggotaController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/test', function () {
    return response()->json(['test' => 'true'], 200);
});

Route::prefix('anggota')->as('anggota')->group(function () {
    Route::post('', [ApiAnggotaController::class, 'index'])->name('anggota.index');
});
