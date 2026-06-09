@extends('layouts.app')

@section('title', 'Daftar Buku')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>
        <i class="bi bi-book"></i>
        Daftar Buku
    </h1>
    <div class="d-flex gap-2">
        {{-- Tombol Export CSV --}}
        <a href="{{ route('buku.export') }}" class="btn btn-success">
            <i class="bi bi-download"></i> Export CSV
        </a>
        
        {{-- Tombol Tambah Buku --}}
        <a href="{{ route('buku.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Tambah Buku
        </a>
    </div>
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

{{-- Form Advanced Search & Filter --}}
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
{{-- Form Bulk Delete membungkus daftar buku --}}
<form action="{{ route('buku.bulk-delete') }}" method="POST" id="form-bulk-delete">
    @csrf

    {{-- Header Bulk Action & Select All --}}
    @if ($bukus->count() > 0)
        <div class="d-flex justify-content-between align-items-center mb-3 bg-white p-3 shadow-sm rounded border">
            <div class="form-check mb-0">
                <input class="form-check-input shadow-sm" type="checkbox" id="select-all" style="transform: scale(1.2);">
                <label class="form-check-label fw-bold ms-2 cursor-pointer" for="select-all">
                    Pilih Semua Buku
                </label>
            </div>
            <button type="button" class="btn btn-danger" id="btn-bulk-delete" disabled>
                <i class="bi bi-trash"></i> Hapus Terpilih (<span id="count-selected">0</span>)
            </button>
        </div>
    @endif

    {{-- Daftar Buku --}}
    @forelse ($bukus as $buku)
        <div class="card mb-3 position-relative">
            <div class="card-body">
                <div class="row align-items-center">
                    
                    {{-- Checkbox Individual --}}
                    <div class="col-md-1 text-center border-end">
                        <input type="checkbox" name="buku_ids[]" value="{{ $buku->id }}" class="form-check-input cb-buku shadow-sm" style="transform: scale(1.5);">
                    </div>

                    {{-- Cover/Icon --}}
                    <div class="col-md-2 text-center">
                        <i class="bi bi-book text-primary" style="font-size: 3rem;"></i>
                        <div class="mt-1">
                            <span class="badge bg-{{ $buku->kategori == 'Programming' ? 'primary' : ($buku->kategori == 'Database' ? 'success' : ($buku->kategori == 'Web Design' ? 'info' : ($buku->kategori == 'Networking' ? 'warning' : 'danger'))) }}">
                                {{ $buku->kategori }}
                            </span>
                        </div>
                    </div>
                    
                    {{-- Info Buku --}}
                    <div class="col-md-6">
                        <h5 class="card-title mb-1">
                            <a href="{{ route('buku.show', $buku->id) }}" class="text-decoration-none">
                                {{ $buku->judul }}
                            </a>
                        </h5>
                        <p class="card-text text-muted mb-2 small">
                            <i class="bi bi-person"></i> {{ $buku->pengarang }} | 
                            <i class="bi bi-building"></i> {{ $buku->penerbit }} | 
                            <i class="bi bi-calendar"></i> {{ $buku->tahun_terbit }}
                        </p>
                        @if ($buku->deskripsi)
                            <p class="card-text small text-muted">
                                {{ Str::limit($buku->deskripsi, 100) }}
                            </p>
                        @endif
                    </div>
                    
                    {{-- Harga & Aksi Individual --}}
                    <div class="col-md-3 text-end">
                        <h5 class="text-primary mb-2">{{ $buku->harga_format }}</h5>
                        <div class="mb-2">
                            @if ($buku->stok > 0)
                                <span class="badge bg-success"><i class="bi bi-check-circle"></i> Stok: {{ $buku->stok }}</span>
                            @else
                                <span class="badge bg-danger"><i class="bi bi-x-circle"></i> Habis</span>
                            @endif
                        </div>
                        <div class="btn-group btn-group-sm w-100">
                            <a href="{{ route('buku.show', $buku->id) }}" class="btn btn-info text-white"><i class="bi bi-eye"></i></a>
                            <a href="{{ route('buku.edit', $buku->id) }}" class="btn btn-warning"><i class="bi bi-pencil"></i></a>
                            
                            {{-- Hapus individual --}}
                            <button type="button" class="btn btn-danger btn-delete-single" data-id="{{ $buku->id }}" data-judul="{{ $buku->judul }}">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="alert alert-warning">
            <i class="bi bi-exclamation-triangle"></i> Data buku tidak ditemukan.
        </div>
    @endforelse
</form>

@if ($bukus->count() > 0)
    <div class="text-center mt-4">
        <p class="text-muted">
            Menampilkan total <strong>{{ $bukus->count() }}</strong> buku.
        </p>
    </div>
@endif
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        
        // --- 1. LOGIKA BULK DELETE (SELECT ALL & CHECKBOXES) ---
        const selectAllCb = document.getElementById('select-all');
        const bukuCbs = document.querySelectorAll('.cb-buku');
        const btnBulkDelete = document.getElementById('btn-bulk-delete');
        const countSelectedSpan = document.getElementById('count-selected');
        const formBulkDelete = document.getElementById('form-bulk-delete');

        // Fungsi untuk mengupdate tombol hapus massal
        function updateBulkButton() {
            if(!btnBulkDelete) return;
            const checkedCount = document.querySelectorAll('.cb-buku:checked').length;
            countSelectedSpan.textContent = checkedCount;
            btnBulkDelete.disabled = checkedCount === 0; // Disable jika 0
        }

        // Event listener "Select All"
        if (selectAllCb) {
            selectAllCb.addEventListener('change', function() {
                bukuCbs.forEach(cb => {
                    cb.checked = this.checked;
                });
                updateBulkButton();
            });
        }

        // Event listener tiap checkbox buku
        bukuCbs.forEach(cb => {
            cb.addEventListener('change', function() {
                // Uncheck "Select All" jika ada satu yg tidak dicentang
                if (!this.checked && selectAllCb) {
                    selectAllCb.checked = false;
                }
                
                // Check "Select All" jika semua dicentang
                const allChecked = document.querySelectorAll('.cb-buku:checked').length === bukuCbs.length;
                if (allChecked && bukuCbs.length > 0 && selectAllCb) {
                    selectAllCb.checked = true;
                }
                
                updateBulkButton();
            });
        });

        // SweetAlert untuk Bulk Delete (Hapus Massal)
        if (btnBulkDelete) {
            btnBulkDelete.addEventListener('click', function() {
                const count = document.querySelectorAll('.cb-buku:checked').length;
                Swal.fire({
                    title: 'Konfirmasi Hapus Massal',
                    text: `Apakah Anda yakin ingin menghapus ${count} buku yang dipilih?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus Semua!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        formBulkDelete.submit();
                    }
                });
            });
        }

        // --- 2. LOGIKA SINGLE DELETE (SweetAlert Satuan) ---
        // Karena form sekarang dipakai untuk bulk delete, hapus satuan kita kirim via form dinamis
        document.querySelectorAll('.btn-delete-single').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const judul = this.getAttribute('data-judul');
                
                Swal.fire({
                    title: 'Konfirmasi Hapus',
                    text: `Apakah Anda yakin ingin menghapus buku "${judul}"?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Buat form on-the-fly untuk menghapus satuan agar tidak bentrok dengan form bulk
                        const form = document.createElement('form');
                        form.action = `/buku/${id}`;
                        form.method = 'POST';
                        form.innerHTML = `
                            @csrf
                            @method('DELETE')
                        `;
                        document.body.appendChild(form);
                        form.submit();
                    }
                });
            });
        });

    });
</script>
@endpush