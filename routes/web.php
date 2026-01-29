<?php

use App\Http\Controllers\View\Master\ViewAnggotaController;
use App\Http\Controllers\View\Master\ViewBukuController;
use App\Http\Controllers\View\Transaksi\ViewPeminjamanController;
use App\Http\Controllers\View\Transaksi\ViewPengembalianController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\View\DashboardController;

// Public routes
Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [LoginController::class, 'show'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');

// Logout route (protected by auth)
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Protected routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Buku
    Route::prefix('buku')->as('buku.')->group(function () {
        Route::get('', [ViewBukuController::class, 'index'])->name('index');
    });

    // Anggota
    Route::prefix('anggota')->as('anggota.')->group(function () {
        Route::get('', [ViewAnggotaController::class, 'index'])->name('index');
    });

    // Peminjaman
    Route::prefix('peminjaman')->as('peminjaman.')->group(function () {
        Route::get('', [ViewPeminjamanController::class, 'index'])->name('index');
    });

    // Pengembalian
    Route::prefix('pengembalian')->as('pengembalian.')->group(function () {
        Route::get('', [ViewPengembalianController::class, 'index'])->name('index');
    });
});
