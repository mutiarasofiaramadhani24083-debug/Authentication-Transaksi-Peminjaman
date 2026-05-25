<?php

use Illuminate\Support\Facades\Route;
use App\Models\Buku;
use App\Models\Anggota;

// ─────────────────────────────────────────────
//  Route utama aplikasi
// ─────────────────────────────────────────────
Route::get('/', function () {
    return view('welcome');
});

// ─────────────────────────────────────────────
//  Tugas 2C – Testing Accessor & Scope
//  URL: /test-accessor-scope
// ─────────────────────────────────────────────
Route::get('/test-accessor-scope', function () {

    // ── Header halaman ────────────────────────
    $html  = '<!DOCTYPE html>';
    $html .= '<html lang="id"><head>';
    $html .= '<meta charset="UTF-8">';
    $html .= '<meta name="viewport" content="width=device-width, initial-scale=1">';
    $html .= '<title>Testing Accessor & Scope</title>';
    $html .= '<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">';
    $html .= '</head><body class="p-4">';

    $html .= '<h1 class="mb-4">🧪 Testing Accessor &amp; Scope</h1>';

    // ── SEKSI 1: Semua Buku + status_stok_badge ──
    $html .= '<div class="card mb-4">';
    $html .= '<div class="card-header bg-primary text-white"><h4 class="mb-0">📚 Semua Buku – Status Stok Badge</h4></div>';
    $html .= '<div class="card-body"><div class="table-responsive">';
    $html .= '<table class="table table-bordered table-striped">';
    $html .= '<thead class="table-dark"><tr><th>#</th><th>Judul</th><th>Stok</th><th>Status Badge</th><th>Tahun Label</th></tr></thead>';
    $html .= '<tbody>';

    $semuaBuku = Buku::all();
    foreach ($semuaBuku as $i => $buku) {
        $html .= '<tr>';
        $html .= '<td>' . ($i + 1) . '</td>';
        $html .= '<td>' . e($buku->judul) . '</td>';
        $html .= '<td>' . $buku->stok . '</td>';
        $html .= '<td>' . $buku->status_stok_badge . '</td>';          // accessor
        $html .= '<td>' . e($buku->tahun_label) . '</td>';              // accessor
        $html .= '</tr>';
    }

    $html .= '</tbody></table></div></div></div>';

    // ── SEKSI 2: Scope – Buku Terbaru (tahun >= 2024) ──
    $html .= '<div class="card mb-4">';
    $html .= '<div class="card-header bg-success text-white"><h4 class="mb-0">🆕 Buku Terbaru (tahun_terbit ≥ 2024)</h4></div>';
    $html .= '<div class="card-body"><div class="table-responsive">';
    $html .= '<table class="table table-bordered">';
    $html .= '<thead class="table-dark"><tr><th>#</th><th>Judul</th><th>Tahun Terbit</th><th>Stok</th></tr></thead>';
    $html .= '<tbody>';

    $bukuTerbaru = Buku::terbaru()->get();
    if ($bukuTerbaru->isEmpty()) {
        $html .= '<tr><td colspan="4" class="text-center text-muted">Tidak ada buku terbaru.</td></tr>';
    } else {
        foreach ($bukuTerbaru as $i => $buku) {
            $html .= '<tr>';
            $html .= '<td>' . ($i + 1) . '</td>';
            $html .= '<td>' . e($buku->judul) . '</td>';
            $html .= '<td>' . e($buku->tahun_terbit) . '</td>';
            $html .= '<td>' . $buku->stok . '</td>';
            $html .= '</tr>';
        }
    }

    $html .= '</tbody></table></div></div></div>';

    // ── SEKSI 3: Scope – Buku Stok Menipis (stok < 5) ──
    $html .= '<div class="card mb-4">';
    $html .= '<div class="card-header bg-warning text-dark"><h4 class="mb-0">⚠️ Buku Stok Menipis (stok &lt; 5)</h4></div>';
    $html .= '<div class="card-body"><div class="table-responsive">';
    $html .= '<table class="table table-bordered">';
    $html .= '<thead class="table-dark"><tr><th>#</th><th>Judul</th><th>Stok</th><th>Status Badge</th></tr></thead>';
    $html .= '<tbody>';

    $bukuMenipis = Buku::stokMenipis()->get();
    if ($bukuMenipis->isEmpty()) {
        $html .= '<tr><td colspan="4" class="text-center text-muted">Tidak ada buku stok menipis.</td></tr>';
    } else {
        foreach ($bukuMenipis as $i => $buku) {
            $html .= '<tr>';
            $html .= '<td>' . ($i + 1) . '</td>';
            $html .= '<td>' . e($buku->judul) . '</td>';
            $html .= '<td>' . $buku->stok . '</td>';
            $html .= '<td>' . $buku->status_stok_badge . '</td>';      // accessor
            $html .= '</tr>';
        }
    }

    $html .= '</tbody></table></div></div></div>';

    // ── SEKSI 4: Semua Anggota + status_badge + kategori_usia ──
    $html .= '<div class="card mb-4">';
    $html .= '<div class="card-header bg-info text-white"><h4 class="mb-0">👤 Semua Anggota – Status Badge &amp; Kategori Usia</h4></div>';
    $html .= '<div class="card-body"><div class="table-responsive">';
    $html .= '<table class="table table-bordered table-striped">';
    $html .= '<thead class="table-dark"><tr><th>#</th><th>Nama</th><th>Jenis Kelamin</th><th>Status Badge</th><th>Kategori Usia</th></tr></thead>';
    $html .= '<tbody>';

    $semuaAnggota = Anggota::all();
    foreach ($semuaAnggota as $i => $anggota) {
        $html .= '<tr>';
        $html .= '<td>' . ($i + 1) . '</td>';
        $html .= '<td>' . e($anggota->nama) . '</td>';
        $html .= '<td>' . e($anggota->jenis_kelamin) . '</td>';
        $html .= '<td>' . $anggota->status_badge . '</td>';             // accessor
        $html .= '<td>' . e($anggota->kategori_usia) . '</td>';         // accessor
        $html .= '</tr>';
    }

    $html .= '</tbody></table></div></div></div>';

    // ── SEKSI 5: Scope – Anggota Terdaftar Bulan Ini ──
    $html .= '<div class="card mb-4">';
    $html .= '<div class="card-header bg-secondary text-white"><h4 class="mb-0">📅 Anggota Terdaftar Bulan Ini</h4></div>';
    $html .= '<div class="card-body"><div class="table-responsive">';
    $html .= '<table class="table table-bordered">';
    $html .= '<thead class="table-dark"><tr><th>#</th><th>Nama</th><th>Tanggal Daftar</th><th>Status Badge</th></tr></thead>';
    $html .= '<tbody>';

    $anggotaBulanIni = Anggota::terdaftarBulanIni()->get();
    if ($anggotaBulanIni->isEmpty()) {
        $html .= '<tr><td colspan="4" class="text-center text-muted">Tidak ada anggota baru bulan ini.</td></tr>';
    } else {
        foreach ($anggotaBulanIni as $i => $anggota) {
            $html .= '<tr>';
            $html .= '<td>' . ($i + 1) . '</td>';
            $html .= '<td>' . e($anggota->nama) . '</td>';
            $html .= '<td>' . $anggota->created_at->format('d M Y') . '</td>';
            $html .= '<td>' . $anggota->status_badge . '</td>';         // accessor
            $html .= '</tr>';
        }
    }

    $html .= '</tbody></table></div></div></div>';

    $html .= '</body></html>';

    return $html;
});