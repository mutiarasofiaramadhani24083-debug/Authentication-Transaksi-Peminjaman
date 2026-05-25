@extends('layouts.app')

@section('title', 'Hasil Pencarian: ' . $keyword)

@section('content')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('kategori.index') }}">Kategori Buku</a></li>
        <li class="breadcrumb-item active" aria-current="page">Pencarian</li>
    </ol>
</nav>

<h3 class="mb-4">Hasil Pencarian untuk: <span class="text-primary">"{{ $keyword }}"</span></h3>

@if(count($hasil_pencarian) > 0)
    <div class="row">
        @foreach ($hasil_pencarian as $kategori)
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm border-primary">
                <div class="card-body">
                    <h5 class="card-title">
                        {!! preg_replace("/(" . preg_quote($keyword, '/') . ")/i", "<mark class='bg-warning'>$1</mark>", $kategori['nama']) !!}
                    </h5>
                    <p class="card-text text-muted">{{ $kategori['deskripsi'] }}</p>
                </div>
                <div class="card-footer bg-white border-top-0">
                    <a href="{{ route('kategori.show', $kategori['id']) }}" class="btn btn-outline-primary w-100">Lihat Detail</a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
@else
    <div class="alert alert-warning">
        Maaf, tidak ada kategori yang cocok dengan kata kunci <strong>"{{ $keyword }}"</strong>.
    </div>
@endif

<a href="{{ route('kategori.index') }}" class="btn btn-secondary mt-3">Kembali ke Daftar Kategori</a>
@endsection