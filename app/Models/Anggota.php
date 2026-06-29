<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Anggota extends Model
{
    use HasFactory;

    protected $table = 'anggota';

    protected $fillable = [
        'kode_anggota',
        'nama',
        'email',
        'telepon',
        'alamat',
        'jenis_kelamin',
        'tanggal_lahir',
        'pekerjaan',
        'tanggal_daftar',
        'status',
        'created_at',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'tanggal_daftar' => 'date',
        'created_at'    => 'datetime',
    ];

    // =========================================================
    //  ACCESSORS
    // =========================================================

    /**
     * Accessor: status_badge
     * Mengembalikan HTML badge Bootstrap berdasarkan status anggota.
     *
     * Aktif    → badge success   "Aktif"
     * Nonaktif → badge secondary "Nonaktif"
     */
    public function getStatusBadgeAttribute(): string
    {
        if ($this->status === 'aktif') {
            return '<span class="badge bg-success">Aktif</span>';
        }

        return '<span class="badge bg-secondary">Nonaktif</span>';
    }

    /**
     * Accessor: kategori_usia
     * Menghitung usia dari tanggal_lahir dan mengembalikan kategori.
     *
     * Umur < 20        → "Remaja"
     * Umur 20–50       → "Dewasa"
     * Umur > 50        → "Senior"
     */
    public function getKategoriUsiaAttribute(): string
    {
        if (!$this->tanggal_lahir) {
            return 'Tidak Diketahui';
        }

        $umur = Carbon::parse($this->tanggal_lahir)->age;

        if ($umur < 20) {
            return 'Remaja';
        } elseif ($umur <= 50) {
            return 'Dewasa';
        } else {
            return 'Senior';
        }
    }

    // =========================================================
    //  SCOPES
    // =========================================================

    /**
     * Scope: jenisKelamin
     * Filter anggota berdasarkan jenis kelamin ('L' atau 'P').
     *
     * Penggunaan: Anggota::jenisKelamin('L')->get()
     */
    public function scopeJenisKelamin($query, $jk)
    {
        return $query->where('jenis_kelamin', $jk);
    }

    /**
     * Scope: terdaftarBulanIni
     * Filter anggota yang terdaftar (created_at) pada bulan & tahun ini.
     *
     * Penggunaan: Anggota::terdaftarBulanIni()->get()
     */
    public function scopeTerdaftarBulanIni($query)
    {
        return $query->whereMonth('created_at', Carbon::now()->month)
                     ->whereYear('created_at', Carbon::now()->year);
    }
    public function transaksis()
{
    return $this->hasMany(Transaksi::class);
}
}