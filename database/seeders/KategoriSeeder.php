<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Kategori;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kategoriData = [
            [
                'nama_kategori' => 'Programming',
                'deskripsi'     => 'Buku-buku tentang pemrograman dan pengembangan perangkat lunak.',
                'icon'          => 'code-slash',
                'warna'         => 'primary',
            ],
            [
                'nama_kategori' => 'Database',
                'deskripsi'     => 'Buku-buku tentang sistem manajemen basis data dan SQL.',
                'icon'          => 'database',
                'warna'         => 'success',
            ],
            [
                'nama_kategori' => 'Web Design',
                'deskripsi'     => 'Buku-buku tentang desain antarmuka web dan pengalaman pengguna.',
                'icon'          => 'palette',
                'warna'         => 'info',
            ],
            [
                'nama_kategori' => 'Networking',
                'deskripsi'     => 'Buku-buku tentang jaringan komputer dan keamanan siber.',
                'icon'          => 'wifi',
                'warna'         => 'warning',
            ],
            [
                'nama_kategori' => 'Data Science',
                'deskripsi'     => 'Buku-buku tentang ilmu data, machine learning, dan kecerdasan buatan.',
                'icon'          => 'graph-up',
                'warna'         => 'danger',
            ],
        ];

        foreach ($kategoriData as $data) {
            Kategori::firstOrCreate(
                ['nama_kategori' => $data['nama_kategori']],
                $data
            );
        }

        $this->command->info('✅ KategoriSeeder berhasil dijalankan! 5 kategori ditambahkan.');
    }
}