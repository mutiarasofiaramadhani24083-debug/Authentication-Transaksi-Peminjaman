<?php

namespace App\Http\Controllers;

use App\Rules\KodeBukuFormat;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use App\Models\Buku;
// Form request bawaan dinonaktifkan sementara karena kita memindahkan
// logika validasinya langsung ke dalam controller ini.
// use App\Http\Requests\StoreBukuRequest;
// use App\Http\Requests\UpdateBukuRequest;

class BukuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Ambil semua data buku dari database
        $bukus = Buku::latest()->get();
        
        // Statistik untuk card
        $totalBuku = Buku::count();
        $bukuTersedia = Buku::where('stok', '>', 0)->count();
        $bukuHabis = Buku::where('stok', 0)->count();
        
        // Return view dengan data
        return view('buku.index', compact(
            'bukus',
            'totalBuku',
            'bukuTersedia',
            'bukuHabis'
        ));
    }

    public function search(Request $request)
    {
        $query = Buku::query();

        // 1. Filter Kata Kunci (Judul, Pengarang, Penerbit)
        if ($request->has('keyword') && $request->keyword != '') {
            $keyword = $request->keyword;
            $query->where(function($q) use ($keyword) {
                $q->where('judul', 'like', "%{$keyword}%")
                  ->orWhere('pengarang', 'like', "%{$keyword}%")
                  ->orWhere('penerbit', 'like', "%{$keyword}%");
            });
        }

        // 2. Filter Kategori (Dropdown)
        if ($request->has('kategori') && $request->kategori != '') {
            $query->where('kategori', $request->kategori);
        }

        // 3. Filter Tahun (Dropdown)
        if ($request->has('tahun') && $request->tahun != '') {
            $query->where('tahun_terbit', $request->tahun);
        }

        // 4. Filter Ketersediaan (Semua / Tersedia / Habis)
        if ($request->has('stok_status') && $request->stok_status != '') {
            if ($request->stok_status == 'tersedia') {
                $query->where('stok', '>', 0);
            } elseif ($request->stok_status == 'habis') {
                $query->where('stok', '<=', 0);
            }
        }

        // Ambil data hasil filter
        $bukus = $query->latest()->get();

        // Dapatkan semua data buku untuk kalkulasi statistik & dropdown tahun di view
        $allBuku = Buku::all();
        $totalBuku = $allBuku->count();
        $bukuTersedia = $allBuku->where('stok', '>', 0)->count();
        $bukuHabis = $allBuku->where('stok', '<=', 0)->count();
        
        $daftarTahun = Buku::select('tahun_terbit')->distinct()->orderBy('tahun_terbit', 'desc')->pluck('tahun_terbit');

        return view('buku.index', compact('bukus', 'totalBuku', 'bukuTersedia', 'bukuHabis', 'daftarTahun'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Akan diimplementasi di pertemuan 12
        return view('buku.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // --- LOGIKA VALIDASI ADVANCED TUGAS 1 ---
        $bahasaRule = ['required', 'string'];
        if ($request->kategori == 'Programming') {
            $bahasaRule[] = 'in:Inggris,inggris,INGGRIS';
        }

        $stokRule = ['required', 'integer', 'min:0'];
        if ($request->tahun_terbit < 2000) {
            $stokRule[] = 'max:5';
        }

        $messages = [
            'kode_buku.required' => 'Kode buku wajib diisi.',
            'kode_buku.unique' => 'Kode buku sudah terdaftar di sistem.',
            'judul.required' => 'Judul buku wajib diisi.',
            'kategori.required' => 'Kategori buku wajib dipilih.',
            'pengarang.required' => 'Nama pengarang wajib diisi.',
            'penerbit.required' => 'Nama penerbit wajib diisi.',
            'tahun_terbit.required' => 'Tahun terbit wajib diisi.',
            'tahun_terbit.numeric' => 'Tahun terbit harus berupa angka valid.',
            'bahasa.required' => 'Bahasa buku wajib diisi.',
            'bahasa.in' => 'Untuk kategori Programming, bahasa harus "Inggris".',
            'harga.required' => 'Harga wajib diisi.',
            'harga.numeric' => 'Harga harus berupa format angka.',
            'stok.required' => 'Stok buku wajib diisi.',
            'stok.numeric' => 'Stok harus berupa angka.',
            'stok.min' => 'Stok tidak boleh kurang dari 0.',
            'stok.max' => 'Buku terbitan sebelum tahun 2000 maksimal memiliki stok 5.',
        ];

        $validated = $request->validate([
            'kode_buku' => ['required', new KodeBukuFormat(), 'unique:bukus,kode_buku'],
            'judul' => 'required|string|max:255',
            'kategori' => 'required|string',
            'pengarang' => 'required|string|max:255',
            'penerbit' => 'required|string|max:255',
            'tahun_terbit' => 'required|numeric|digits:4',
            'bahasa' => $bahasaRule,
            'harga' => 'required|numeric|min:0',
            'stok' => $stokRule,
            'deskripsi' => 'nullable|string',
            'isbn' => 'nullable|string'
        ], $messages);
        // ----------------------------------------

        try {
            // Create buku baru dengan validated data
            Buku::create($validated);
            
            // Redirect dengan success message
            return redirect()->route('buku.index')
                             ->with('success', 'Buku berhasil ditambahkan!');
                             
        } catch (\Exception $e) {
            // Redirect dengan error message jika gagal
            return redirect()->back()
                             ->withInput()
                             ->with('error', 'Gagal menambahkan buku: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Find buku by ID, throw 404 if not found
        $buku = Buku::findOrFail($id);
        
        // Return view detail buku
        return view('buku.show', compact('buku'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Akan diimplementasi di pertemuan 12
        $buku = Buku::findOrFail($id);
        return view('buku.edit', compact('buku'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $buku = Buku::findOrFail($id);
            
            // --- LOGIKA VALIDASI ADVANCED TUGAS 1 ---
            $bahasaRule = ['required', 'string'];
            if ($request->kategori == 'Programming') {
                $bahasaRule[] = 'in:Inggris,inggris,INGGRIS';
            }

            $stokRule = ['required', 'integer', 'min:0'];
            if ($request->tahun_terbit < 2000) {
                $stokRule[] = 'max:5';
            }

            $messages = [
                'kode_buku.required' => 'Kode buku wajib diisi.',
                'kode_buku.unique' => 'Kode buku sudah terdaftar di sistem.',
                'judul.required' => 'Judul buku wajib diisi.',
                'kategori.required' => 'Kategori buku wajib dipilih.',
                'pengarang.required' => 'Nama pengarang wajib diisi.',
                'penerbit.required' => 'Nama penerbit wajib diisi.',
                'tahun_terbit.required' => 'Tahun terbit wajib diisi.',
                'tahun_terbit.numeric' => 'Tahun terbit harus berupa angka valid.',
                'bahasa.required' => 'Bahasa buku wajib diisi.',
                'bahasa.in' => 'Untuk kategori Programming, bahasa harus "Inggris".',
                'harga.required' => 'Harga wajib diisi.',
                'harga.numeric' => 'Harga harus berupa format angka.',
                'stok.required' => 'Stok buku wajib diisi.',
                'stok.numeric' => 'Stok harus berupa angka.',
                'stok.min' => 'Stok tidak boleh kurang dari 0.',
                'stok.max' => 'Buku terbitan sebelum tahun 2000 maksimal memiliki stok 5.',
            ];

            $validated = $request->validate([
                'kode_buku' => ['required', new KodeBukuFormat(), 'unique:bukus,kode_buku,' . $buku->id],
                'judul' => 'required|string|max:255',
                'kategori' => 'required|string',
                'pengarang' => 'required|string|max:255',
                'penerbit' => 'required|string|max:255',
                'tahun_terbit' => 'required|numeric|digits:4',
                'bahasa' => $bahasaRule,
                'harga' => 'required|numeric|min:0',
                'stok' => $stokRule,
                'deskripsi' => 'nullable|string',
                'isbn' => 'nullable|string'
            ], $messages);
            // ----------------------------------------

            // Update buku dengan validated data
            $buku->update($validated);
            
            // Redirect dengan success message
            return redirect()->route('buku.show', $buku->id)
                             ->with('success', 'Buku berhasil diupdate!');
                             
        } catch (\Exception $e) {
            // Redirect dengan error message jika gagal
            return redirect()->back()
                             ->withInput()
                             ->with('error', 'Gagal mengupdate buku: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $buku = Buku::findOrFail($id);
            $judulBuku = $buku->judul;
            
            // Delete buku
            $buku->delete();
            
            // Redirect dengan success message
            return redirect()->route('buku.index')
                             ->with('success', "Buku '{$judulBuku}' berhasil dihapus!");
                             
        } catch (\Exception $e) {
            // Redirect dengan error message jika gagal
            return redirect()->back()
                             ->with('error', 'Gagal menghapus buku: ' . $e->getMessage());
        }
    }
    
    /**
     * Filter buku berdasarkan kategori.
     */
    public function filterKategori($kategori)
    {
        $bukus = Buku::where('kategori', $kategori)->latest()->get();
        
        $totalBuku = $bukus->count();
        $bukuTersedia = $bukus->where('stok', '>', 0)->count();
        $bukuHabis = $bukus->where('stok', 0)->count();
        
        return view('buku.index', compact(
            'bukus',
            'totalBuku',
            'bukuTersedia',
            'bukuHabis',
            'kategori'
        ));
    }

    public function bulkDelete(Request $request)
    {
        // Validasi agar minimal ada 1 checkbox yang dipilih
        if (!$request->has('buku_ids') || empty($request->buku_ids)) {
            return redirect()->back()->with('error', 'Pilih minimal satu buku untuk dihapus.');
        }

        try {
            $ids = $request->buku_ids;
            
            // Hapus data yang ID-nya ada di dalam array $ids
            Buku::whereIn('id', $ids)->delete();
            
            return redirect()->route('buku.index')
                             ->with('success', count($ids) . ' buku berhasil dihapus massal!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus buku: ' . $e->getMessage());
        }
    }
    public function export()
    {
        $bukus = Buku::all();
        
        $filename = 'buku_' . date('Y-m-d_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($bukus) {
            $file = fopen('php://output', 'w');
            
            // Header CSV
            fputcsv($file, [
                'Kode Buku', 'Judul', 'Kategori', 'Pengarang', 
                'Penerbit', 'Tahun', 'ISBN', 'Harga', 'Stok'
            ]);
            
            // Data
            foreach ($bukus as $buku) {
                fputcsv($file, [
                    $buku->kode_buku,
                    $buku->judul,
                    $buku->kategori,
                    $buku->pengarang,
                    $buku->penerbit,
                    $buku->tahun_terbit,
                    $buku->isbn,
                    $buku->harga,
                    $buku->stok,
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}