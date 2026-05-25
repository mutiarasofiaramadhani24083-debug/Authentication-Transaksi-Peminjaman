<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class KategoriController extends Controller
{
    // Simulasi data kategori (digunakan di beberapa method)
    private function getKategoriList()
    {
        return [
            ['id' => 1, 'nama' => 'Programming', 'deskripsi' => 'Buku pemrograman dan algoritma coding', 'jumlah_buku' => 25],
            ['id' => 2, 'nama' => 'Sistem Informasi', 'deskripsi' => 'Buku tentang analisis sistem dan basis data', 'jumlah_buku' => 18],
            ['id' => 3, 'nama' => 'Kecerdasan Buatan', 'deskripsi' => 'Buku machine learning dan artificial intelligence', 'jumlah_buku' => 12],
            ['id' => 4, 'nama' => 'Jaringan Komputer', 'deskripsi' => 'Buku seputar topologi, server, dan keamanan jaringan', 'jumlah_buku' => 20],
            ['id' => 5, 'nama' => 'Desain UI/UX', 'deskripsi' => 'Buku desain antarmuka dan pengalaman pengguna', 'jumlah_buku' => 15],
        ];
    }

    public function index()
    {
        $kategori_list = $this->getKategoriList();
        
        return view('kategori.index', compact('kategori_list'));
    }
    
    public function show($id)
    {
        // Mencari kategori berdasarkan ID
        $kategori = collect($this->getKategoriList())->firstWhere('id', (int)$id);
        
        if (!$kategori) {
            abort(404, 'Kategori tidak ditemukan');
        }

        // Simulasi data buku berdasarkan ID kategori
        $semua_buku = [
            1 => [
                ['judul' => 'Clean Code', 'penulis' => 'Robert C. Martin', 'tahun' => 2008],
                ['judul' => 'Belajar Laravel 10', 'penulis' => 'Taylor Otwell', 'tahun' => 2023]
            ],
            2 => [
                ['judul' => 'Sistem Informasi Manajemen', 'penulis' => 'Kenneth C. Laudon', 'tahun' => 2020]
            ],
            3 => [
                ['judul' => 'Artificial Intelligence: A Modern Approach', 'penulis' => 'Stuart Russell', 'tahun' => 2021]
            ],
            4 => [
                ['judul' => 'Computer Networking', 'penulis' => 'James F. Kurose', 'tahun' => 2017]
            ],
            5 => [
                ['judul' => 'Don\'t Make Me Think', 'penulis' => 'Steve Krug', 'tahun' => 2014]
            ]
        ];

        $buku_list = $semua_buku[$id] ?? [];
        
        return view('kategori.show', compact('kategori', 'buku_list'));
    }
    
    public function search($keyword)
    {
        $kategori_list = $this->getKategoriList();
        
        // Filter kategori jika nama mengandung keyword (case-insensitive)
        $hasil_pencarian = array_filter($kategori_list, function($kategori) use ($keyword) {
            return stripos($kategori['nama'], $keyword) !== false;
        });

        return view('kategori.search', compact('hasil_pencarian', 'keyword'));
    }
}