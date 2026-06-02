<?php
 
namespace App\Http\Controllers;
 
use Illuminate\Http\Request;
use App\Models\Buku;
use App\Models\Anggota;
 
class DashboardController extends Controller
{
    public function index()
    {
        // Statistik Buku (Menggunakan Eloquent yang sudah menerapkan Prepared Statement)
        $totalBuku    = Buku::count();
        $bukuTersedia = Buku::where('stok', '>', 0)->count();
        $bukuHabis    = Buku::where('stok', 0)->count();
        
        // Statistik Anggota
        $totalAnggota    = Anggota::count();
        $anggotaAktif    = Anggota::where('status', 'Aktif')->count();
        $anggotaNonaktif = Anggota::where('status', 'Nonaktif')->count();
        
        // Mengambil 5 Data Terbaru
        $bukuTerbaru    = Buku::latest()->take(5)->get();
        $anggotaTerbaru = Anggota::latest()->take(5)->get();
        
        return view('dashboard.index', compact(
            'totalBuku',
            'bukuTersedia',
            'bukuHabis',
            'totalAnggota',
            'anggotaAktif',
            'anggotaNonaktif',
            'bukuTerbaru',
            'anggotaTerbaru'
        ));
    }
}