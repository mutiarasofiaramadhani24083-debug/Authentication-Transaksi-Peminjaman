@extends('layouts.app')
 
@section('title', 'Dashboard Perpustakaan')
 
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>
        <i class="bi bi-speedometer2"></i> Dashboard Perpustakaan UIN Gusdur Pekalongan
    </h1>
</div>

{{-- Quick Links --}}
<div class="card mb-4 border-0 shadow-sm bg-light">
    <div class="card-body">
        <h6 class="text-muted fw-bold mb-3"><i class="bi bi-link-45deg"></i> Quick Links Menu Utama</h6>
        <div class="d-flex gap-2">
            <a href="{{ route('buku.index') }}" class="btn btn-primary"><i class="bi bi-book"></i> Kelola Buku</a>
            <a href="{{ route('anggota.index') }}" class="btn btn-success"><i class="bi bi-people"></i> Kelola Anggota</a>
            <a href="{{ route('buku.create') }}" class="btn btn-outline-primary"><i class="bi bi-plus-circle"></i> Tambah Buku Baru</a>
            <a href="{{ route('anggota.create') }}" class="btn btn-outline-success"><i class="bi bi-person-plus"></i> Tambah Anggota Baru</a>
        </div>
    </div>
</div>

<div class="row">
    {{-- Kolom Statistik Buku --}}
    <div class="col-md-6 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-primary text-white fw-bold">
                <i class="bi bi-book-half"></i> Ringkasan Buku
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-4">
                        <h3 class="text-primary">{{ $totalBuku }}</h3>
                        <span class="text-muted small">Total Buku</span>
                    </div>
                    <div class="col-4">
                        <h3 class="text-success">{{ $bukuTersedia }}</h3>
                        <span class="text-muted small">Tersedia</span>
                    </div>
                    <div class="col-4">
                        <h3 class="text-danger">{{ $bukuHabis }}</h3>
                        <span class="text-muted small">Habis</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Kolom Statistik Anggota --}}
    <div class="col-md-6 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-success text-white fw-bold">
                <i class="bi bi-people-fill"></i> Ringkasan Anggota
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-4">
                        <h3 class="text-success">{{ $totalAnggota }}</h3>
                        <span class="text-muted small">Total Anggota</span>
                    </div>
                    <div class="col-4">
                        <h3 class="text-info">{{ $anggotaAktif }}</h3>
                        <span class="text-muted small">Aktif</span>
                    </div>
                    <div class="col-4">
                        <h3 class="text-secondary">{{ $anggotaNonaktif }}</h3>
                        <span class="text-muted small">Nonaktif</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    {{-- Tabel 5 Buku Terbaru --}}
    <div class="col-md-6 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-dark text-white fw-bold">
                <i class="bi bi-journal-text"></i> 5 Buku Terbaru
            </div>
            <div class="card-body p-0">
                <table class="table table-striped table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Judul</th>
                            <th>Kategori</th>
                            <th>Stok</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bukuTerbaru as $buku)
                        <tr>
                            <td><a href="{{ route('buku.show', $buku->id) }}" class="text-decoration-none fw-semibold">{{ $buku->judul }}</a></td>
                            <td><span class="badge bg-secondary">{{ $buku->kategori }}</span></td>
                            <td>
                                @if($buku->stok > 0)
                                    <span class="text-success fw-bold">{{ $buku->stok }}</span>
                                @else
                                    <span class="badge bg-danger">Habis</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted">Belum ada data buku</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Tabel 5 Anggota Terbaru --}}
    <div class="col-md-6 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-dark text-white fw-bold">
                <i class="bi bi-person-lines-fill"></i> 5 Anggota Terbaru
            </div>
            <div class="card-body p-0">
                <table class="table table-striped table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Nama</th>
                            <th>Status</th>
                            <th>Didaftarkan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($anggotaTerbaru as $anggota)
                        <tr>
                            <td><a href="{{ route('anggota.show', $anggota->id) }}" class="text-decoration-none fw-semibold">{{ $anggota->nama }}</a></td>
                            <td>
                                @if($anggota->status == 'Aktif')
                                    <span class="badge bg-success">Aktif</span>
                                @else
                                    <span class="badge bg-secondary">Nonaktif</span>
                                @endif
                            </td>
                            <td class="small text-muted">{{ $anggota->created_at->diffForHumans() }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted">Belum ada data anggota</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection