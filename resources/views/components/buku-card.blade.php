<div class="card mb-3 shadow-sm border-0">
    <div class="card-body">
        <div class="row align-items-center">
            
            {{-- 1. Cover Icon & Badge Kategori --}}
            <div class="col-md-2 text-center mb-3 mb-md-0">
                <i class="bi bi-book text-primary" style="font-size: 3.5rem;"></i>
                <div class="mt-2">
                    @php
                        // Menentukan warna badge berdasarkan kategori
                        $badgeColor = match($buku->kategori) {
                            'Programming' => 'primary',
                            'Database'    => 'success',
                            'Web Design'  => 'info',
                            'Networking'  => 'warning',
                            default       => 'secondary'
                        };
                    @endphp
                    <span class="badge bg-{{ $badgeColor }}">
                        {{ $buku->kategori }}
                    </span>
                </div>
            </div>
            
            {{-- 2. Judul, Pengarang, dan Harga --}}
            <div class="col-md-7">
                <h5 class="card-title mb-1">
                    <a href="{{ route('buku.show', $buku->id) }}" class="text-decoration-none fw-bold text-dark">
                        {{ $buku->judul }}
                    </a>
                </h5>
                <p class="text-muted mb-2">
                    <i class="bi bi-person"></i> {{ $buku->pengarang }}
                </p>
                <h5 class="text-success fw-bold mb-0">
                    {{ $buku->harga_format }}
                </h5>
            </div>
            
            {{-- 3. Status Stok & Actions --}}
            <div class="col-md-3 text-md-end text-start border-md-start mt-3 mt-md-0">
                <div class="mb-3">
                    @if ($buku->stok > 0)
                        <span class="badge bg-success mb-1">
                            <i class="bi bi-check-circle"></i> Tersedia
                        </span>
                        <div class="text-muted small">
                            Stok: {{ $buku->stok }} buku
                        </div>
                    @else
                        <span class="badge bg-danger">
                            <i class="bi bi-x-circle"></i> Habis
                        </span>
                    @endif
                </div>
                
                {{-- Tampilkan tombol Aksi HANYA JIKA $showActions = true --}}
                @if($showActions)
                    <div class="btn-group w-100" role="group">
                        <a href="{{ route('buku.show', $buku->id) }}" class="btn btn-sm btn-info text-white" title="Detail">
                            <i class="bi bi-eye"></i> Detail
                        </a>
                        <a href="{{ route('buku.edit', $buku->id) }}" class="btn btn-sm btn-warning" title="Edit">
                            <i class="bi bi-pencil"></i> Edit
                        </a>
                    </div>
                @endif
            </div>
            
        </div>
    </div>
</div>