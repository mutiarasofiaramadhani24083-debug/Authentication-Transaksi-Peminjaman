@extends('layouts.app')

@section('title', 'Detail Kategori: ' . $kategori['nama'])

@section('content')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('kategori.index') }}">Kategori Buku</a></li>
        <li class="breadcrumb-item active" aria-current="page">{{ $kategori['nama'] }}</li>
    </ol>
</nav>

<div class="card shadow-sm mb-4">
    <div class="card-body">
        <h3 class="card-title text-success">{{ $kategori['nama'] }}</h3>
        <p class="card-text">{{ $kategori['deskripsi'] }}</p>
        <span class="badge bg-info text-dark fs-6">Total Buku: {{ $kategori['jumlah_buku'] }}</span>
    </div>
</div>

<h4>Daftar Buku dalam Kategori Ini</h4>
<div class="table-responsive">
    <table class="table table-bordered table-striped mt-3">
        <thead class="table-dark">
            <tr>
                <th style="width: 5%">No</th>
                <th>Judul Buku</th>
                <th>Penulis</th>
                <th>Tahun Terbit</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($buku_list as $index => $buku)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $buku['judul'] }}</td>
                <td>{{ $buku['penulis'] }}</td>
                <td>{{ $buku['tahun'] }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="text-center">Belum ada data buku untuk kategori ini.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<a href="{{ route('kategori.index') }}" class="btn btn-secondary mt-3">Kembali ke Daftar Kategori</a>
@endsection