<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang digunakan oleh model ini.
     */
    protected $table = 'kategori';

    /**
     * Atribut yang boleh diisi secara massal (mass assignment).
     */
    protected $fillable = [
        'nama_kategori',
        'deskripsi',
        'icon',
        'warna',
    ];
}