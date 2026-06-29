<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Detail Transaksi Pengembalian
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            
            {{-- LANGKAH 4: WARNING ALERT SESUAI INSTRUKSI --}}
            @if($transaksi->status == 'Dipinjam' && $transaksi->terlambat > 0)
                <div class="alert alert-danger shadow-sm rounded-xl mb-6">
                    <h4 class="alert-heading font-bold">⚠️ Peringatan Keterlambatan!</h4>
                    <p>Transaksi ini sudah melewati batas waktu peminjaman selama <strong>{{ $transaksi->terlambat }} hari</strong>.</p>
                    <hr>
                    <p class="mb-0">Denda berjalan: <strong>Rp {{ number_format($transaksi->terlambat * 5000, 0, ',', '.') }}</strong></p>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm rounded-3xl border border-gray-100 p-8">
                {{-- Sisa konten lainnya tetap sama --}}
                <div class="flex justify-between items-center mb-6 border-b pb-4">
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-purple-600 bg-purple-100 px-3 py-1 rounded-full">
                            {{ $transaksi->kode_transaksi }}
                        </span>
                        <h3 class="text-xl font-extrabold text-gray-800 mt-2">Informasi Peminjaman</h3>
                    </div>
                    <div>
                        <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full {{ $transaksi->status == 'Dipinjam' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}">
                            {{ $transaksi->status }}
                        </span>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div>
                        <label class="text-xs font-semibold text-gray-400 uppercase">Nama Anggota</label>
                        <p class="text-base font-bold text-gray-800 mt-1">{{ $transaksi->anggota->nama }}</p>
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-gray-400 uppercase">Judul Buku</label>
                        <p class="text-base font-bold text-gray-800 mt-1">📚 {{ $transaksi->buku->judul }}</p>
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-gray-400 uppercase">Tanggal Pinjam</label>
                        <p class="text-base font-medium text-gray-800 mt-1">
                            {{ \Carbon\Carbon::parse($transaksi->tanggal_pinjam)->format('d M Y') }}
                        </p>
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-gray-400 uppercase">Batas Pengembalian</label>
                        <p class="text-base font-medium text-red-600 mt-1">
                            {{ \Carbon\Carbon::parse($transaksi->tanggal_pinjam)->addDays(7)->format('d M Y') }}
                        </p>
                    </div>
                </div>

                <div class="bg-gray-50 rounded-2xl p-5 border border-gray-100 mb-8">
                    <h4 class="text-sm font-bold text-gray-700 mb-3">Status Pengembalian & Denda</h4>
                    
                    @if($transaksi->status == 'Dipinjam')
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Perkiraan Denda Saat Ini:</span>
                            <span class="text-lg font-extrabold {{ $perkiraanDenda > 0 ? 'text-red-600' : 'text-green-600' }}">
                                Rp {{ number_format($perkiraanDenda, 0, ',', '.') }}
                            </span>
                        </div>
                    @else
                        <div class="space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Tanggal Dikembalikan:</span>
                                <span class="font-semibold text-gray-800">{{ \Carbon\Carbon::parse($transaksi->tanggal_kembali)->format('d M Y') }}</span>
                            </div>
                            <div class="flex justify-between text-sm items-center">
                                <span class="text-gray-600">Total Denda Dibayar:</span>
                                <span class="text-lg font-extrabold {{ $transaksi->denda > 0 ? 'text-red-600' : 'text-green-600' }}">
                                    Rp {{ number_format($transaksi->denda, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="flex justify-between items-center">
                    <a href="{{ route('transaksi.index') }}" class="text-sm font-medium text-gray-600 hover:text-gray-800 transition">
                        ← Kembali ke Daftar
                    </a>

                    @if($transaksi->status == 'Dipinjam')
                        <form action="{{ route('transaksi.kembalikan', $transaksi->id) }}" method="POST" onsubmit="return confirm('Apakah kamu yakin ingin mengembalikan buku ini?')">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-xl font-bold shadow-sm transition transform hover:-translate-y-0.5">
                                ✨ Kembalikan Buku
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>