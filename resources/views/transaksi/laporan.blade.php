<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Laporan Transaksi Perpustakaan
        </h2>
    </x-slot>

    <div class="container mt-4">
        <div class="card mb-4 shadow-sm border-0 rounded-2xl">
            <div class="card-header bg-light border-0 py-3">
                <h5 class="mb-0 font-bold text-gray-700">🔍 Filter Laporan</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('transaksi.laporan') }}" method="GET" class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label text-xs font-semibold text-gray-500 uppercase">Dari Tanggal</label>
                        <input type="date" name="start_date" class="form-control rounded-xl" value="{{ request('start_date') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label text-xs font-semibold text-gray-500 uppercase">Sampai Tanggal</label>
                        <input type="date" name="end_date" class="form-control rounded-xl" value="{{ request('end_date') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label text-xs font-semibold text-gray-500 uppercase">Status</label>
                        <select name="status" class="form-select rounded-xl">
                            <option value="Semua" {{ request('status') == 'Semua' ? 'selected' : '' }}>Semua Status</option>
                            <option value="Dipinjam" {{ request('status') == 'Dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                            <option value="Dikembalikan" {{ request('status') == 'Dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label text-xs font-semibold text-gray-500 uppercase">Nama Anggota</label>
                        <select name="anggota_id" class="form-select rounded-xl">
                            <option value="">Semua Anggota</option>
                            @foreach($anggotaList as $agt)
                                <option value="{{ $agt->id }}" {{ request('anggota_id') == $agt->id ? 'selected' : '' }}>
                                    {{ $agt->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12 d-flex justify-content-between mt-4">
                        <div>
                            <button type="submit" class="btn btn-primary px-4 font-bold rounded-xl shadow-sm">Filter</button>
                            <a href="{{ route('transaksi.laporan') }}" class="btn btn-secondary px-3 ms-2 rounded-xl">Reset</a>
                        </div>
                        <div>
                            <a href="{{ route('transaksi.exportPdf', request()->all()) }}" class="btn btn-danger px-4 font-bold rounded-xl shadow-sm flex items-center">
                                📄 Export ke PDF
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card border-0 bg-blue-50 rounded-2xl p-3 shadow-sm">
                    <div class="card-body text-center">
                        <h6 class="text-blue-600 text-xs font-bold uppercase mb-1">Total Transaksi</h6>
                        <h2 class="font-extrabold text-blue-900 mb-0">{{ $totalTransaksi }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card border-0 bg-red-50 rounded-2xl p-3 shadow-sm">
                    <div class="card-body text-center">
                        <h6 class="text-red-600 text-xs font-bold uppercase mb-1">Total Pendapatan Denda</h6>
                        <h2 class="font-extrabold text-red-900 mb-0">Rp {{ number_format($totalDenda, 0, ',', '.') }}</h2>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0 rounded-2xl overflow-hidden">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th class="px-4 py-3">No</th>
                            <th>Kode</th>
                            <th>Anggota</th>
                            <th>Buku</th>
                            <th>Tgl Pinjam</th>
                            <th>Tgl Kembali</th>
                            <th>Denda</th>
                            <th class="px-4">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transaksis as $transaksi)
                        <tr>
                            <td class="px-4">{{ $loop->iteration }}</td>
                            <td><code>{{ $transaksi->kode_transaksi }}</code></td>
                            <td class="font-semibold text-gray-800">{{ $transaksi->anggota->nama }}</td>
                            <td>{{ $transaksi->buku->judul }}</td>
                            <td>{{ $transaksi->tanggal_pinjam->format('d M Y') }}</td>
                            <td>{{ $transaksi->tanggal_kembali ? $transaksi->tanggal_kembali->format('d M Y') : '-' }}</td>
                            <td class="font-bold {{ $transaksi->denda > 0 ? 'text-danger' : 'text-success' }}">
                                Rp {{ number_format($transaksi->denda, 0, ',', '.') }}
                            </td>
                            <td class="px-4">
                                @if($transaksi->status == 'Dipinjam')
                                    <span class="badge bg-warning text-dark px-2.5 py-1.5 rounded-full">Dipinjam</span>
                                @else
                                    <span class="badge bg-success px-2.5 py-1.5 rounded-full">Dikembalikan</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                Tidak ada data transaksi yang sesuai dengan kriteria filter.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>