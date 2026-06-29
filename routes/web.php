<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PerpustakaanController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\KategoriController;
use Illuminate\Support\Facades\Route;

// ==================== HALAMAN UTAMA ====================
Route::get('/', function () {
    return view('welcome');
});

// ==================== PERPUSTAKAAN ====================
Route::get('/perpustakaan', [PerpustakaanController::class, 'index'])->name('perpustakaan.index');
Route::get('/perpustakaan/about', [PerpustakaanController::class, 'about'])->name('perpustakaan.about');
Route::get('/perpustakaan/{id}', [PerpustakaanController::class, 'show'])->name('perpustakaan.show');

// ==================== BUKU (Resource + Extra) ====================
Route::resource('buku', BukuController::class);
Route::get('/buku-search', [BukuController::class, 'search'])->name('buku.search');
Route::get('/buku-filter/{kategori}', [BukuController::class, 'filterKategori'])->name('buku.filterKategori');
Route::post('/buku-bulk-delete', [BukuController::class, 'bulkDelete'])->name('buku.bulkDelete');
Route::get('/buku-export-csv', [BukuController::class, 'exportCsv'])->name('buku.exportCsv');

// ==================== ANGGOTA (Resource + Extra) ====================
Route::resource('anggota', AnggotaController::class);
Route::get('/anggota-search', [AnggotaController::class, 'search'])->name('anggota.search');
Route::get('/anggota-export', [AnggotaController::class, 'export'])->name('anggota.export');

// ==================== TRANSAKSI ====================
Route::get('/transaksi/laporan', [TransaksiController::class, 'laporan'])->name('transaksi.laporan');
Route::get('/transaksi/laporan/pdf', [TransaksiController::class, 'exportPdf'])->name('transaksi.exportPdf');
Route::put('/transaksi/{id}/kembalikan', [TransaksiController::class, 'kembalikan'])->name('transaksi.kembalikan');
Route::resource('transaksi', TransaksiController::class);

// ==================== KATEGORI ====================
Route::get('/kategori', [KategoriController::class, 'index'])->name('kategori.index');
Route::get('/kategori/{id}', [KategoriController::class, 'show'])->name('kategori.show');
Route::get('/kategori-search/{keyword}', [KategoriController::class, 'search'])->name('kategori.search');

// ==================== DASHBOARD ====================
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// ==================== PROFILE (Breeze) ====================
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';