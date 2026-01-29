<?php

use App\Http\Controllers\Api\Master\AnggotaController;
use App\Http\Controllers\Api\Master\ApiAnggotaController;
use App\Http\Controllers\Api\Master\ApiBukuController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/test', function () {
    return response()->json(['test' => 'true'], 200);
});

Route::prefix('anggota')->group(function () {
    Route::post('', [ApiAnggotaController::class, 'index'])->name('api.anggota.index');
});

Route::prefix('buku')->group(function () {
    Route::post('', [ApiBukuController::class, 'index'])->name('api.buku.index');
    Route::post('store', [ApiBukuController::class, 'store'])->name('api.buku.store');
    Route::get('{id}', [ApiBukuController::class, 'show'])->name('api.buku.show');
    Route::patch('{id}', [ApiBukuController::class, 'update'])->name('api.buku.update');
    Route::delete('{id}', [ApiBukuController::class, 'destroy'])->name('api.buku.destroy');
});
