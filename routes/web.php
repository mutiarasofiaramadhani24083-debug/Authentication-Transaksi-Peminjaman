<?php
 
use Illuminate\Support\Facades\Route;

// Data anggota diletakkan di luar route agar bisa diakses oleh kedua route menggunakan 'use'
$anggota_list = [
    [
        'id' => 1,
        'kode' => 'AGT-001',
        'nama' => 'Budi Santoso',
        'email' => 'budi@email.com',
        'telepon' => '081234567890',
        'alamat' => 'Jakarta',
        'status' => 'Aktif'
    ],
    [
        'id' => 2,
        'kode' => 'AGT-002',
        'nama' => 'Siti Aminah',
        'email' => 'siti@email.com',
        'telepon' => '082345678901',
        'alamat' => 'Bandung',
        'status' => 'Aktif'
    ],
    [
        'id' => 3,
        'kode' => 'AGT-003',
        'nama' => 'Andi Wijaya',
        'email' => 'andi@email.com',
        'telepon' => '083456789012',
        'alamat' => 'Surabaya',
        'status' => 'Non-Aktif'
    ],
    [
        'id' => 4,
        'kode' => 'AGT-004',
        'nama' => 'Rina Melati',
        'email' => 'rina@email.com',
        'telepon' => '084567890123',
        'alamat' => 'Yogyakarta',
        'status' => 'Aktif'
    ],
    [
        'id' => 5,
        'kode' => 'AGT-005',
        'nama' => 'Joko Anwar',
        'email' => 'joko@email.com',
        'telepon' => '085678901234',
        'alamat' => 'Semarang',
        'status' => 'Non-Aktif'
    ],
];

// Route untuk menampilkan daftar anggota
Route::get('/anggota', function () use ($anggota_list) {
    return view('anggota.index', ['anggota_list' => $anggota_list]);
});

// Route untuk menampilkan detail anggota berdasarkan ID
Route::get('/anggota/{id}', function ($id) use ($anggota_list) {
    // Mencari anggota berdasarkan ID menggunakan collection
    $anggota = collect($anggota_list)->firstWhere('id', (int)$id);
    
    // Jika data tidak ditemukan, tampilkan halaman 404
    if (!$anggota) {
        abort(404, 'Data Anggota Tidak Ditemukan');
    }

    return view('anggota.show', ['anggota' => $anggota]);
});

use App\Http\Controllers\KategoriController;

Route::get('/kategori', [KategoriController::class, 'index'])->name('kategori.index');
Route::get('/kategori/search/{keyword}', [KategoriController::class, 'search'])->name('kategori.search');
Route::get('/kategori/{id}', [KategoriController::class, 'show'])->name('kategori.show');