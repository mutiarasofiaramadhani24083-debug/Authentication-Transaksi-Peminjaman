@extends('layouts.app')
 
@section('title', 'Daftar Buku')
 
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>
        <i class="bi bi-book"></i>
        Daftar Buku
    </h1>
    <a href="{{ route('buku.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Tambah Buku
    </a>
</div>
 
{{-- Statistik Cards --}}
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card border-primary">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Total Buku</h6>
                        <h2 class="mb-0">{{ $totalBuku }}</h2>
                    </div>
                    <div class="text-primary">
                        <i class="bi bi-book-fill" style="font-size: 3rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card border-success">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Buku Tersedia</h6>
                        <h2 class="mb-0">{{ $bukuTersedia }}</h2>
                    </div>
                    <div class="text-success">
                        <i class="bi bi-check-circle-fill" style="font-size: 3rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card border-danger">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Buku Habis</h6>
                        <h2 class="mb-0">{{ $bukuHabis }}</h2>
                    </div>
                    <div class="text-danger">
                        <i class="bi bi-x-circle-fill" style="font-size: 3rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
 
{{-- INI BAGIAN YANG BARU: Form Advanced Search & Filter --}}
<div class="card mb-4 shadow-sm border-0 bg-light">
    <div class="card-body">
        <form action="{{ route('buku.search') }}" method="GET">
            <div class="row g-3 align-items-end">
                {{-- Input Keyword --}}
                <div class="col-md-3">
                    <label class="form-label fw-bold text-muted small">Pencarian</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                        <input type="text" name="keyword" class="form-control" placeholder="Judul, pengarang..." value="{{ request('keyword') }}">
                    </div>
                </div>
                
                {{-- Dropdown Kategori --}}
                <div class="col-md-2">
                    <label class="form-label fw-bold text-muted small">Kategori</label>
                    <select name="kategori" class="form-select">
                        <option value="Semua">Semua Kategori</option>
                        <option value="Programming" {{ request('kategori') == 'Programming' ? 'selected' : '' }}>Programming</option>
                        <option value="Database" {{ request('kategori') == 'Database' ? 'selected' : '' }}>Database</option>
                        <option value="Web Design" {{ request('kategori') == 'Web Design' ? 'selected' : '' }}>Web Design</option>
                        <option value="Networking" {{ request('kategori') == 'Networking' ? 'selected' : '' }}>Networking</option>
                        <option value="Data Science" {{ request('kategori') == 'Data Science' ? 'selected' : '' }}>Data Science</option>
                    </select>
                </div>
                
                {{-- Dropdown Tahun --}}
                <div class="col-md-2">
                    <label class="form-label fw-bold text-muted small">Tahun Terbit</label>
                    <select name="tahun" class="form-select">
                        <option value="Semua">Semua Tahun</option>
                        @foreach($tahunList ?? [] as $t)
                            <option value="{{ $t }}" {{ request('tahun') == $t ? 'selected' : '' }}>{{ $t }}</option>
                        @endforeach
                    </select>
                </div>
                
                {{-- Dropdown Ketersediaan --}}
                <div class="col-md-2">
                    <label class="form-label fw-bold text-muted small">Ketersediaan</label>
                    <select name="ketersediaan" class="form-select">
                        <option value="Semua">Semua Status</option>
                        <option value="Tersedia" {{ request('ketersediaan') == 'Tersedia' ? 'selected' : '' }}>Tersedia</option>
                        <option value="Habis" {{ request('ketersediaan') == 'Habis' ? 'selected' : '' }}>Habis</option>
                    </select>
                </div>
                
                {{-- Tombol Aksi --}}
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary w-100 mb-2">
                        <i class="bi bi-funnel-fill"></i> Terapkan Filter
                    </button>
                    <a href="{{ route('buku.index') }}" class="btn btn-outline-secondary w-100">
                        <i class="bi bi-arrow-counterclockwise"></i> Reset
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Info Hasil Pencarian --}}
@if(request()->has('keyword') || (request()->has('kategori') && request('kategori') != 'Semua'))
    <div class="alert alert-info mb-4">
        <i class="bi bi-info-circle-fill"></i> Menampilkan hasil pencarian. Ditemukan <strong>{{ $bukus->count() }}</strong> buku.
    </div>
@endif
 
{{-- Daftar Buku --}}
@forelse ($bukus as $buku)
    <div class="card mb-3">
        <div class="card-body">
            <div class="row">
                <div class="col-md-2 text-center">
                    <i class="bi bi-book text-primary" style="font-size: 4rem;"></i>
                    <div class="mt-2">
                        <span class="badge bg-{{ $buku->kategori == 'Programming' ? 'primary' : ($buku->kategori == 'Database' ? 'success' : ($buku->kategori == 'Web Design' ? 'info' : ($buku->kategori == 'Networking' ? 'warning' : 'danger'))) }}">
                            {{ $buku->kategori }}
                        </span>
                    </div>
                </div>
                
                <div class="col-md-7">
                    <h5 class="card-title">
                        <a href="{{ route('buku.show', $buku->id) }}" class="text-decoration-none">
                            {{ $buku->judul }}
                        </a>
                    </h5>
                    
                    <p class="card-text text-muted mb-2">
                        <i class="bi bi-person"></i> {{ $buku->pengarang }} | 
                        <i class="bi bi-building"></i> {{ $buku->penerbit }} | 
                        <i class="bi bi-calendar"></i> {{ $buku->tahun_terbit }}
                    </p>
                    
                    @if ($buku->isbn)
                        <p class="card-text small text-muted mb-1">
                            <i class="bi bi-upc"></i> ISBN: {{ $buku->isbn }}
                        </p>
                    @endif
                    
                    @if ($buku->deskripsi)
                        <p class="card-text">
                            {{ Str::limit($buku->deskripsi, 150) }}
                        </p>
                    @endif
                </div>
                
                <div class="col-md-3 text-end">
                    <h4 class="text-primary mb-2">
                        {{ $buku->harga_format }}
                    </h4>
                    
                    <div class="mb-3">
                        @if ($buku->stok > 0)
                            <span class="badge bg-success">
                                <i class="bi bi-check-circle"></i> Tersedia
                            </span>
                            <div class="text-muted small mt-1">
                                Stok: {{ $buku->stok }} buku
                            </div>
                        @else
                            <span class="badge bg-danger">
                                <i class="bi bi-x-circle"></i> Habis
                            </span>
                        @endif
                    </div>
                    
                    <div class="btn-group-vertical d-grid gap-2">
                        <a href="{{ route('buku.show', $buku->id) }}" class="btn btn-sm btn-info text-white">
                            <i class="bi bi-eye"></i> Detail
                        </a>
                        <a href="{{ route('buku.edit', $buku->id) }}" class="btn btn-sm btn-warning">
                            <i class="bi bi-pencil"></i> Edit
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@empty
    <div class="alert alert-warning">
        <i class="bi bi-exclamation-triangle"></i>
        Data buku tidak ditemukan untuk pencarian ini.
    </div>
@endforelse
 
@if ($bukus->count() > 0)
    <div class="text-center mt-4">
        <p class="text-muted">
            Menampilkan total <strong>{{ $bukus->count() }}</strong> buku.
        </p>
    </div>
@endif
@endsection