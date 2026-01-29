<?php

use App\Http\Controllers\Api\Master\AnggotaController;
use App\Http\Controllers\Api\Master\ApiAnggotaController;
use App\Http\Controllers\Api\Master\ApiBukuController;
use App\Http\Controllers\Api\Transaksi\ApiPeminjamanController;
use App\Http\Controllers\Api\Transaksi\ApiPengembalianController;
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
    Route::get('get-latest-number', [ApiAnggotaController::class, 'getNomorAnggota'])->name('api.anggota.get-anggota-number');
    Route::post('store', [ApiAnggotaController::class, 'store'])->name('api.anggota.store');
    Route::get('{id}', [ApiAnggotaController::class, 'show'])->name('api.anggota.show');
    Route::patch('{id}', [ApiAnggotaController::class, 'update'])->name('api.anggota.update');
    Route::delete('{id}', [ApiAnggotaController::class, 'destroy'])->name('api.anggota.destroy');
});

Route::prefix('buku')->group(function () {
    Route::post('', [ApiBukuController::class, 'index'])->name('api.buku.index');
    Route::post('store', [ApiBukuController::class, 'store'])->name('api.buku.store');
    Route::get('{id}', [ApiBukuController::class, 'show'])->name('api.buku.show');
    Route::patch('{id}', [ApiBukuController::class, 'update'])->name('api.buku.update');
    Route::delete('{id}', [ApiBukuController::class, 'destroy'])->name('api.buku.destroy');
});

Route::prefix('peminjaman')->group(function () {
    Route::post('', [ApiPeminjamanController::class, 'index'])->name('api.peminjaman.index');
    Route::post('store', [ApiPeminjamanController::class, 'store'])->name('api.peminjaman.store');
    Route::get('{id}', [ApiPeminjamanController::class, 'show'])->name('api.peminjaman.show');
    Route::patch('{id}', [ApiPeminjamanController::class, 'update'])->name('api.peminjaman.update');
    Route::delete('{id}', [ApiPeminjamanController::class, 'destroy'])->name('api.peminjaman.destroy');
});

Route::prefix('pengembalian')->group(function () {
    Route::post('', [ApiPengembalianController::class, 'index'])->name('api.pengembalian.index');
    Route::post('store', [ApiPengembalianController::class, 'store'])->name('api.pengembalian.store');
    Route::get('{id}', [ApiPengembalianController::class, 'show'])->name('api.pengembalian.show');
    Route::patch('{id}', [ApiPengembalianController::class, 'update'])->name('api.pengembalian.update');
    Route::delete('{id}', [ApiPengembalianController::class, 'destroy'])->name('api.pengembalian.destroy');
});
