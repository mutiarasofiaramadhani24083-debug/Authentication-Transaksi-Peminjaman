<!DOCTYPE html>
<html>
<head>
    <title>Laporan Transaksi Perpustakaan</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 11px; color: #333; line-height: 1.4; }
        .header { text-align: center; margin-bottom: 25px; border-bottom: 3px double #333; padding-bottom: 8px; }
        .header h2 { margin: 0; font-size: 20px; color: #1a252f; text-transform: uppercase; }
        .header p { margin: 4px 0 0 0; font-size: 12px; color: #7f8c8d; }
        .info-cetak { margin-bottom: 15px; background: #f8f9fa; padding: 8px 12px; border-radius: 6px; font-size: 10px; border: 1px solid #e2e8f0; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #cbd5e1; padding: 7px 10px; text-align: left; }
        th { background-color: #f1f5f9; font-weight: bold; color: #334155; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .badge { padding: 3px 6px; border-radius: 4px; font-size: 9px; font-weight: bold; }
        .bg-warning { background: #fef3c7; color: #92400e; border: 1px solid #fde68a; }
        .bg-success { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
        .total-box { float: right; width: 280px; margin-top: 10px; border: 1px solid #cbd5e1; background: #fafafa; border-radius: 6px; padding: 10px; }
        .total-box table { width: 100%; border: none; margin-bottom: 0; }
        .total-box td { border: none; padding: 4px; font-size: 12px; }
    </style>
</head>
<body>

    <div class="header">
        <h2>Laporan Transaksi Perpustakaan</h2>
        <p>Sistem Manajemen Data Peminjaman & Pengembalian Buku</p>
    </div>

    <div class="info-cetak">
        <strong>Filter Periode:</strong> {{ request('start_date') ?: 'Semua' }} s/d {{ request('end_date') ?: 'Hari Ini' }} | 
        <strong>Status:</strong> {{ request('status') ?: 'Semua' }} | 
        <strong>Waktu Cetak:</strong> {{ now()->format('d M Y H:i') }} WIB
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 4%">No</th>
                <th style="width: 14%">Kode Transaksi</th>
                <th style="width: 18%">Nama Anggota</th>
                <th style="width: 22%">Judul Buku</th>
                <th style="width: 11%">Tgl Pinjam</th>
                <th style="width: 11%">Tgl Kembali</th>
                <th style="width: 10%">Besar Denda</th>
                <th style="width: 10%">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transaksis as $index => $transaksi)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td><code>{{ $transaksi->kode_transaksi }}</code></td>
                <td class="font-bold">{{ $transaksi->anggota->nama }}</td>
                <td>{{ $transaksi->buku->judul }}</td>
                <td>{{ $transaksi->tanggal_pinjam->format('d/m/Y') }}</td>
                <td>{{ $transaksi->tanggal_kembali ? $transaksi->tanggal_kembali->format('d/m/Y') : '-' }}</td>
                <td class="font-bold">Rp {{ number_format($transaksi->denda, 0, ',', '.') }}</td>
                <td>
                    <span class="badge {{ $transaksi->status == 'Dipinjam' ? 'bg-warning' : 'bg-success' }}">
                        {{ $transaksi->status }}
                    </span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total-box">
        <table>
            <tr>
                <td>Total Transaksi:</td>
                <td class="text-right font-bold">{{ $totalTransaksi }} Data</td>
            </tr>
            <tr style="color: #b91c1c;">
                <td class="font-bold">Total Akumulasi Denda:</td>
                <td class="text-right font-bold">Rp {{ number_format($totalDenda, 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>

</body>
</html>