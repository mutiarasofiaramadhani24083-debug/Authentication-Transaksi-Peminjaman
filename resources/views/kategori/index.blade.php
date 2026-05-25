@extends('layouts.app')

@section('title', 'Daftar Kategori Buku')

@section('content')
<div class="row mb-3">
    <div class="col-md-8">
        <h2>Daftar Kategori Buku</h2>
    </div>
    <div class="col-md-4">
        <div class="input-group">
            <input type="text" id="searchInput" class="form-control" placeholder="Cari kategori (misal: programming)...">
            <button class="btn btn-primary" onclick="searchKategori()">Cari</button>
        </div>
    </div>
</div>

<div class="row">
    @foreach ($kategori_list as $kategori)
    <div class="col-md-4 mb-4">
        <div class="card h-100 shadow-sm">
            <div class="card-body">
                <h5 class="card-title">{{ $kategori['nama'] }}</h5>
                <p class="card-text text-muted">{{ $kategori['deskripsi'] }}</p>
                <p class="card-text fw-bold">Jumlah Buku: <span class="badge bg-secondary">{{ $kategori['jumlah_buku'] }}</span></p>
            </div>
            <div class="card-footer bg-white border-top-0">
                <a href="{{ route('kategori.show', $kategori['id']) }}" class="btn btn-outline-success w-100">Lihat Detail</a>
            </div>
        </div>
    </div>
    @endforeach
</div>

<script>
    function searchKategori() {
        let keyword = document.getElementById('searchInput').value;
        if(keyword) {
            window.location.href = "/kategori/search/" + keyword;
        }
    }
</script>
@endsection