<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Buku extends Model
{
    use HasFactory;

    protected $table = 'buku';

    protected $fillable = [
        'kode_buku',     
        'judul',
        'kategori',      
        'pengarang',
        'penerbit',
        'tahun_terbit',
        'isbn',          
        'bahasa',       
        'stok',
        'harga',
        'deskripsi',  
    ];

    // =========================================================
    //  ACCESSORS
    // =========================================================

    /**
     * Accessor: status_stok_badge
     * Mengembalikan HTML badge Bootstrap berdasarkan jumlah stok.
     *
     * Stok = 0       → badge danger  "Habis"
     * Stok 1–5       → badge warning "Menipis"
     * Stok 6–15      → badge info    "Sedang"
     * Stok > 15      → badge success "Aman"
     */
    public function getStatusStokBadgeAttribute(): string
    {
        $stok = (int) $this->stok;

        if ($stok === 0) {
            return '<span class="badge bg-danger">Habis</span>';
        } elseif ($stok <= 5) {
            return '<span class="badge bg-warning">Menipis</span>';
        } elseif ($stok <= 15) {
            return '<span class="badge bg-info">Sedang</span>';
        } else {
            return '<span class="badge bg-success">Aman</span>';
        }
    }

    /**
     * Accessor: tahun_label
     * Mengembalikan label berdasarkan tahun terbit.
     *
     * Tahun >= 2024 → "Buku Baru"
     * Tahun <  2024 → "Buku Lama"
     */
    public function getTahunLabelAttribute(): string
    {
        return (int) $this->tahun_terbit >= 2024 ? 'Buku Baru' : 'Buku Lama';
    }

    // =========================================================
    //  SCOPES
    // =========================================================

    /**
     * Scope: stokMenipis
     * Filter buku dengan stok < 5.
     *
     * Penggunaan: Buku::stokMenipis()->get()
     */
    public function scopeStokMenipis($query)
    {
        return $query->where('stok', '<', 5);
    }

    /**
     * Scope: hargaRange
     * Filter buku dengan harga antara $min dan $max.
     *
     * Penggunaan: Buku::hargaRange(50000, 150000)->get()
     */
    public function scopeHargaRange($query, $min, $max)
    {
        return $query->whereBetween('harga', [$min, $max]);
    }

    /**
     * Scope: terbaru
     * Filter buku dengan tahun_terbit >= 2024.
     *
     * Penggunaan: Buku::terbaru()->get()
     */
    public function scopeTerbaru($query)
    {
        return $query->where('tahun_terbit', '>=', 2024);
    }
}