<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\KomponenProdukJadiController;
use App\Http\Controllers\ProdukJadiController;
use App\Http\Controllers\HasilUpperController;
use App\Http\Controllers\DistributeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;

// Home sederhana
Route::get('/', function () {
    return redirect()->route('komponen_produk_jadi.index');
});

// Login
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware('auth');

// 🔧 CRUD Komponen Produk Jadi
Route::resource('komponen_produk_jadi', KomponenProdukJadiController::class);

// 🔧 CRUD Produk Jadi
Route::resource('produk_jadi', ProdukJadiController::class);

// 🔧 Hasil Upper (tanpa edit/destroy)
Route::get('hasil_upper', [HasilUpperController::class, 'index'])->name('hasil_upper.index');
Route::get('hasil_upper/create', [HasilUpperController::class, 'create'])->name('hasil_upper.create');
Route::post('hasil_upper', [HasilUpperController::class, 'store'])->name('hasil_upper.store');
Route::get('/hasil-upper/export', [HasilUpperController::class, 'export'])->name('hasil_upper.export');

// 📦 Distribusi Barang (scan barcode dan FIFO)
Route::get('distribute', [DistributeController::class, 'index'])->name('distribute.index');
Route::post('distribute', [DistributeController::class, 'store'])->name('distribute.store');
Route::delete('/distribute/{id}', [DistributeController::class, 'destroy'])->name('distribute.destroy');
Route::get('/distribute/export', [DistributeController::class, 'export'])->name('distribute.export');

Route::resource('users', UserController::class)->middleware('auth');

