<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Daftar Buku') }}
        </h2>
    </x-slot>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    {{-- KONTEN BUKU KAMU DI SINI --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>
            <i class="bi bi-book"></i>
            Daftar Buku
        </h1>
        <div class="d-flex gap-2">

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
    
    {{-- Filter Kategori --}}
    <div class="card mb-4">
        <div class="card-body">
            <h6 class="card-title">
                <i class="bi bi-funnel"></i> Filter Kategori:
            </h6>
        </div>
    </div>

    @php
        $tahunOptions = $tahunList ?? collect();
        $selectedKategori = request('kategori');
        $selectedTahun = request('tahun');
        $selectedKetersediaan = request('ketersediaan');
    @endphp

    {{-- Search & Filter Advanced --}}
    <div class="card mb-4">
        <div class="card-body">
            <h6 class="card-title">
                <i class="bi bi-search"></i> Pencarian & Filter Advanced
            </h6>
            <form action="{{ route('buku.search') }}" method="GET" class="row g-2 align-items-end">
                <div class="col-md-4">
                    <label class="form-label">Keyword</label>
                    <input
                        type="text"
                        name="keyword"
                        class="form-control"
                        placeholder="Judul, pengarang, penerbit"
                        value="{{ request('keyword') }}"
                    />
                </div>
                <div class="col-md-2">
                    <label class="form-label">Kategori</label>
                    <select name="kategori" class="form-select">
                        <option value="">Semua</option>
                        <option value="Programming" {{ $selectedKategori === 'Programming' ? 'selected' : '' }}>Programming</option>
                        <option value="Database" {{ $selectedKategori === 'Database' ? 'selected' : '' }}>Database</option>
                        <option value="Web Design" {{ $selectedKategori === 'Web Design' ? 'selected' : '' }}>Web Design</option>
                        <option value="Networking" {{ $selectedKategori === 'Networking' ? 'selected' : '' }}>Networking</option>
                        <option value="Data Science" {{ $selectedKategori === 'Data Science' ? 'selected' : '' }}>Data Science</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Tahun</label>
                    <select name="tahun" class="form-select">
                        <option value="">Semua</option>
                        @foreach ($tahunOptions as $tahun)
                            <option value="{{ $tahun }}" {{ (string) $tahun === (string) $selectedTahun ? 'selected' : '' }}>
                                {{ $tahun }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Ketersediaan</label>
                    <select name="ketersediaan" class="form-select">
                        <option value="">Semua</option>
                        <option value="tersedia" {{ $selectedKetersediaan === 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                        <option value="habis" {{ $selectedKetersediaan === 'habis' ? 'selected' : '' }}>Habis</option>
                    </select>
                </div>
                <div class="col-md-2 d-grid gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search"></i> Cari
                    </button>
                    <a href="{{ route('buku.index') }}" class="btn btn-outline-secondary">
                        Reset
                    </a>
                </div>
            </form>
        </div>
    </div>
    
    {{-- Bulk Delete Form --}}
    <form id="bulk-delete-form" action="{{ route('buku.bulkDelete') }}" method="POST" class="mb-4">
        @csrf
        <div class="d-flex gap-2 mb-3">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="select-all">
                <label class="form-check-label" for="select-all">
                    <strong>Pilih Semua</strong>
                </label>
            </div>
            <button type="button" class="btn btn-danger btn-sm" id="bulk-delete-btn" disabled>
                <i class="bi bi-trash"></i> Hapus Terpilih
            </button>
            <span class="text-muted small" id="selected-count">(0 dipilih)</span>
        </div>
    </form>
    <div class="row g-4 mb-4">
        @forelse ($bukus as $buku)
            <div class="col-md-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body d-flex flex-column justify-content-between">
                        <div>
                            <div class="form-check mb-2">
                                <input class="form-check-input buku-checkbox" type="checkbox" name="buku_ids[]"
                                    value="{{ $buku->id }}" form="bulk-delete-form" id="buku-{{ $buku->id }}">
                                <label class="form-check-label small" for="buku-{{ $buku->id }}">
                                    Pilih
                                </label>
                            </div>

                            <span class="badge bg-secondary mb-2">{{ $buku->kategori }}</span>
                            
                            <h5 class="card-title text-primary fw-bold mb-3">{{ $buku->judul }}</h5>
                            
                            <p class="card-text mb-1 text-muted small">
                                <i class="bi bi-person"></i> <strong>Pengarang:</strong> {{ $buku->pengarang }}
                            </p>
                            <p class="card-text mb-1 text-muted small">
                                <i class="bi bi-building"></i> <strong>Penerbit:</strong> {{ $buku->penerbit }}
                            </p>
                            <p class="card-text mb-3 text-muted small">
                                <i class="bi bi-calendar-event"></i> <strong>Tahun:</strong> {{ $buku->tahun }}
                            </p>
                        </div>
                        
                        <div>
                            <hr class="text-muted">
                            {{-- Group Tombol Aksi & Form Delete Gabungan --}}
                            <div class="btn-group-vertical d-grid gap-2">
                                <a href="{{ route('buku.show', $buku->id) }}" class="btn btn-sm btn-info text-white">
                                    <i class="bi bi-eye"></i> Detail
                                </a>
                                <a href="{{ route('buku.edit', $buku->id) }}" class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                                
                                {{-- Delete Button --}}
                                <form action="{{ route('buku.destroy', $buku->id) }}" 
                                    method="POST" 
                                    class="d-inline delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-sm btn-danger w-100 btn-delete" 
                                            data-judul="{{ $buku->judul }}">
                                        <i class="bi bi-trash"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info shadow-sm">
                    <i class="bi bi-info-circle"></i>
                    Tidak ada data buku
                    @isset($kategori)
                        dengan kategori <strong>{{ $kategori }}</strong>
                    @endisset
                </div>
            </div>
        @endforelse
    </div>
    
    {{-- Footer Info Jumlah Data --}}
    @if ($bukus->count() > 0)
        <div class="text-center mt-4">
            <p class="text-muted">
                Menampilkan {{ $bukus->count() }} buku
                @isset($kategori)
                    dari kategori <strong>{{ $kategori }}</strong>
                @endisset
            </p>
        </div>
    @endif

    </div>
        </div>
    </div>
</x-app-layout>
@push('scripts')
<script>
    // Select All Checkbox
    const selectAllCheckbox = document.getElementById('select-all');
    const bukuCheckboxes = document.querySelectorAll('.buku-checkbox');
    const bulkDeleteBtn = document.getElementById('bulk-delete-btn');
    const selectedCount = document.getElementById('selected-count');
    const bulkDeleteForm = document.getElementById('bulk-delete-form');

    function updateSelectedCount() {
        const checkedCount = document.querySelectorAll('.buku-checkbox:checked').length;
        selectedCount.textContent = `(${checkedCount} dipilih)`;
        bulkDeleteBtn.disabled = checkedCount === 0;
    }

    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            bukuCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            updateSelectedCount();
        });
    }

    bukuCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const allChecked = Array.from(bukuCheckboxes).every(cb => cb.checked);
            const anyChecked = Array.from(bukuCheckboxes).some(cb => cb.checked);

            if (selectAllCheckbox) {
                selectAllCheckbox.checked = allChecked;
                selectAllCheckbox.indeterminate = anyChecked && !allChecked;
            }

            updateSelectedCount();
        });
    });

    if (bulkDeleteBtn) {
        bulkDeleteBtn.addEventListener('click', function(e) {
            e.preventDefault();
            const selectedIds = Array.from(document.querySelectorAll('.buku-checkbox:checked')).length;

            if (selectedIds === 0) {
                Swal.fire({
                    title: 'Tidak Ada Data',
                    text: 'Pilih minimal satu buku untuk dihapus.',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                });
                return;
            }

            Swal.fire({
                title: 'Konfirmasi Hapus Massal',
                text: `Apakah Anda yakin ingin menghapus ${selectedIds} buku terpilih?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus Semua!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    bulkDeleteForm.submit();
                }
            });
        });
    }

    // SweetAlert confirmation untuk delete individual
    document.querySelectorAll('.btn-delete').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const form = this.closest('form');
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
                    form.submit();
                }
            });
        });
    });
</script>
@endpush
