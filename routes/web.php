<?php

use App\Http\Controllers\View\Master\ViewAnggotaController;
use App\Http\Controllers\View\Master\ViewBukuController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('buku')->as('buku.')->group(function () {
    Route::get('', [ViewBukuController::class, 'index'])->name('index');
});

Route::prefix('anggota')->as('anggota.')->group(function () {
    Route::get('', [ViewAnggotaController::class, 'index'])->name('index');
    ;
});
