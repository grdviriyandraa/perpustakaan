<?php

namespace Database\Seeders;

use App\Models\KategoriBuku;
use Illuminate\Database\Seeder;

class KategoriBukuSeeder extends Seeder
{
    public function run(): void
    {
        $kategori = [
            ['nama_kategori' => 'Sistem Informasi', 'deskripsi' => 'Analisis dan perancangan sistem informasi.'],
            ['nama_kategori' => 'Basis Data', 'deskripsi' => 'Model data, SQL, dan perancangan basis data.'],
            ['nama_kategori' => 'Pemrograman Web', 'deskripsi' => 'Laravel, HTML, CSS, dan JavaScript.'],
            ['nama_kategori' => 'Manajemen', 'deskripsi' => 'Organisasi, bisnis, dan manajemen proyek.'],
            ['nama_kategori' => 'Jaringan Komputer', 'deskripsi' => 'Infrastruktur jaringan dan keamanan.'],
            ['nama_kategori' => 'Kecerdasan Buatan', 'deskripsi' => 'Machine learning dan AI.'],
            ['nama_kategori' => 'Umum', 'deskripsi' => 'Koleksi umum non-teknis.'],
        ];

        foreach ($kategori as $item) {
            KategoriBuku::firstOrCreate(['nama_kategori' => $item['nama_kategori']], $item);
        }
    }
}
