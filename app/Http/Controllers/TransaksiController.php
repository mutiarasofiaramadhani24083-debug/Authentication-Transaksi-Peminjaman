<?php
 
namespace App\Http\Controllers;
 
use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\Buku;
use App\Models\Anggota;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class TransaksiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $transaksis = Transaksi::with(['anggota', 'buku'])
                               ->latest()
                               ->get();
        
        return view('transaksi.index', compact('transaksis'));
    }
 
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Get only anggota aktif
        $anggotas = Anggota::where('status', 'Aktif')->orderBy('nama')->get();
        
        // Get only buku yang tersedia (stok > 0)
        $bukus = Buku::where('stok', '>', 0)->orderBy('judul')->get();
        
        return view('transaksi.create', compact('anggotas', 'bukus'));
    }
 
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'anggota_id' => 'required|exists:anggota,id',
            'buku_id' => 'required|exists:buku,id',
            'tanggal_pinjam' => 'required|date',
            'keterangan' => 'nullable|string',
        ], [
            'anggota_id.required' => 'Anggota wajib dipilih.',
            'buku_id.required' => 'Buku wajib dipilih.',
            'tanggal_pinjam.required' => 'Tanggal pinjam wajib diisi.',
        ]);
        
        try {
            DB::transaction(function () use ($request) {
                // 1. Check stok buku
                $buku = Buku::findOrFail($request->buku_id);
                if ($buku->stok <= 0) {
                    throw new \Exception('Stok buku habis!');
                }
                
                // 2. Generate kode transaksi
                $kodeTransaksi = $this->generateKodeTransaksi();
                
                // 3. Calculate tanggal kembali (7 hari dari tanggal pinjam)
                $tanggalKembali = Carbon::parse($request->tanggal_pinjam)->addDays(7);
                
                // 4. Create transaksi
                Transaksi::create([
                    'kode_transaksi' => $kodeTransaksi,
                    'anggota_id' => $request->anggota_id,
                    'buku_id' => $request->buku_id,
                    'tanggal_pinjam' => $request->tanggal_pinjam,
                    'tanggal_kembali' => $tanggalKembali,
                    'status' => 'Dipinjam',
                    'keterangan' => $request->keterangan,
                ]);
                
                // 5. Update stok buku (kurang 1)
                $buku->decrement('stok');
            });
            
            return redirect()->route('transaksi.index')
                             ->with('success', 'Transaksi peminjaman berhasil dibuat!');
                             
        } catch (\Exception $e) {
            return redirect()->back()
                             ->withInput()
                             ->with('error', 'Gagal membuat transaksi: ' . $e->getMessage());
        }
    }
 
    /**
     * Display the specified resource.
     */
    public function show($id)
{
    // Mengambil data transaksi beserta relasi anggota dan buku
    $transaksi = Transaksi::with(['anggota', 'buku'])->findOrFail($id);
    
    // Hitung perkiraan denda berjalan (jika belum dikembalikan dan sudah telat)
    $perkiraanDenda = 0;
    if ($transaksi->status == 'Dipinjam') {
        $tglPinjam = Carbon::parse($transaksi->tanggal_pinjam);
        $batasKembali = $tglPinjam->copy()->addDays(7)->startOfDay(); // Batas 7 hari
        $hariIni = now()->startOfDay();

        if ($hariIni->gt($batasKembali)) {
            $selisihHari = $hariIni->diffInDays($batasKembali);
            $perkiraanDenda = $selisihHari * 5000;
        }
    }

    return view('transaksi.show', compact('transaksi', 'perkiraanDenda'));
}

// 2. Method untuk memproses pengembalian buku
public function kembalikan($id)
{
    $transaksi = Transaksi::findOrFail($id);

    // Validasi pencegahan jika statusnya sudah dikembalikan
    if ($transaksi->status == 'Dikembalikan') {
        return redirect()->back()->with('error', 'Buku ini sudah dikembalikan sebelumnya!');
    }

    $tglPinjam = Carbon::parse($transaksi->tanggal_pinjam);
    $tglKembali = now()->startOfDay();
    
    // Menghitung batas tanggal pengembalian (tgl pinjam + 7 hari)
    $batasKembali = $tglPinjam->copy()->addDays(7)->startOfDay();
    
    $denda = 0;
    // Jika tanggal kembali melewati batas tanggal harus kembali
    if ($tglKembali->gt($batasKembali)) {
        $selisihHari = $tglKembali->diffInDays($batasKembali);
        $denda = $selisihHari * 5000; // Rp 5.000 per hari
    }

    // 1. Update status transaksi, tanggal kembali, dan jumlah denda
    $transaksi->update([
        'status' => 'Dikembalikan',
        'tanggal_kembali' => $tglKembali,
        'denda' => $denda
    ]);

    // 2. Update Stok Buku: bertambah 1
    $transaksi->buku->increment('stok');

    // Redirect kembali ke halaman index dengan flash message sukses
    return redirect()->route('transaksi.index')->with('success', 'Buku berhasil dikembalikan. ' . ($denda > 0 ? 'Denda keterlambatan: Rp ' . number_format($denda, 0, ',', '.') : 'Tepat waktu, tanpa denda!'));
}
 
    /**
     * Generate kode transaksi otomatis.
     */
    private function generateKodeTransaksi()
    {
        $lastTransaksi = Transaksi::latest()->first();
        
        if ($lastTransaksi) {
            $lastNumber = intval(substr($lastTransaksi->kode_transaksi, -3));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        
        return 'TRX-' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
    }
 
    /**
     * Hitung denda keterlambatan.
     */
    private function hitungDenda($transaksi, $tanggalDikembalikan)
    {
        $hariTerlambat = $transaksi->tanggal_kembali->diffInDays($tanggalDikembalikan, false);
        
        if ($hariTerlambat > 0) {
            // Denda Rp 5.000 per hari
            return $hariTerlambat * 5000;
        }
        
        return 0;
    }

    public function laporan(Request $request)
{
    // Mengambil semua anggota untuk dropdown filter
    $anggotaList = Anggota::orderBy('nama', 'asc')->get();

    // Query utama transaksi beserta relasinya
    $query = Transaksi::with(['anggota', 'buku']);

    // Filter 1: Berdasarkan Range Tanggal (Dari - Sampai)
    if ($request->filled('start_date') && $request->filled('end_date')) {
        $query->whereBetween('tanggal_pinjam', [$request->start_date, $request->end_date]);
    }

    // Filter 2: Berdasarkan Status Peminjaman
    if ($request->filled('status') && $request->status != 'Semua') {
        $query->where('status', $request->status);
    }

    // Filter 3: Berdasarkan Dropdown Anggota
    if ($request->filled('anggota_id')) {
        $query->where('anggota_id', $request->anggota_id);
    }

    // Ambil data hasil filter
    $transaksis = $query->latest()->get();

    // Hitung akumulasi statistik sesuai spesifikasi tugas
    $totalTransaksi = $transaksis->count();
    $totalDenda = $transaksis->sum('denda');

    return view('transaksi.laporan', compact('transaksis', 'anggotaList', 'totalTransaksi', 'totalDenda'));
}

public function exportPdf(Request $request)
{
    // Logika filter disamakan dengan fungsi laporan di atas
    $query = Transaksi::with(['anggota', 'buku']);

    if ($request->filled('start_date') && $request->filled('end_date')) {
        $query->whereBetween('tanggal_pinjam', [$request->start_date, $request->end_date]);
    }
    if ($request->filled('status') && $request->status != 'Semua') {
        $query->where('status', $request->status);
    }
    if ($request->filled('anggota_id')) {
        $query->where('anggota_id', $request->anggota_id);
    }

    $transaksis = $query->latest()->get();
    $totalTransaksi = $transaksis->count();
    $totalDenda = $transaksis->sum('denda');

    // Load file blade khusus layout cetak PDF
    $pdf = Pdf::loadView('transaksi.laporan-pdf', compact('transaksis', 'totalTransaksi', 'totalDenda', 'request'))
              ->setPaper('a4', 'landscape'); // Atur kertas A4 posisi Landscape agar tabel muat

    return $pdf->download('Laporan_Transaksi_Perpustakaan.pdf');
}
}