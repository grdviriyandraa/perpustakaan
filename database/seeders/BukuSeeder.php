<?php

namespace Database\Seeders;

use App\Models\Buku;
use App\Models\KategoriBuku;
use Illuminate\Database\Seeder;

class BukuSeeder extends Seeder
{
    public function run(): void
    {
        $kategori = KategoriBuku::pluck('id', 'nama_kategori');

        $buku = [
            ['kode_buku' => 'BK-001', 'judul' => 'Dasar Sistem Informasi', 'penulis' => 'R. Santoso', 'penerbit' => 'Lentera Press', 'tahun_terbit' => 2022, 'kategori' => 'Sistem Informasi', 'lokasi_rak' => 'R-01', 'stok' => 3, 'cover_color' => 'cover-a', 'deskripsi' => 'Pengantar konsep sistem informasi, komponen, dan penerapannya dalam organisasi.'],
            ['kode_buku' => 'BK-002', 'judul' => 'Analisis Sistem Modern', 'penulis' => 'A. Wijaya', 'penerbit' => 'Akademika', 'tahun_terbit' => 2023, 'kategori' => 'Sistem Informasi', 'lokasi_rak' => 'R-02', 'stok' => 2, 'cover_color' => 'cover-b', 'deskripsi' => 'Metode analisis kebutuhan sistem, UML, dan studi kasus perancangan.'],
            ['kode_buku' => 'BK-003', 'judul' => 'Pemodelan Data', 'penulis' => 'N. Putri', 'penerbit' => 'Lentera Press', 'tahun_terbit' => 2021, 'kategori' => 'Basis Data', 'lokasi_rak' => 'R-03', 'stok' => 2, 'cover_color' => 'cover-c', 'deskripsi' => 'Konsep data, relasi, CDM, PDM, dan pemetaan kebutuhan sistem ke rancangan basis data.'],
            ['kode_buku' => 'BK-004', 'judul' => 'SQL untuk Profesional', 'penulis' => 'D. Rahman', 'penerbit' => 'Kode Media', 'tahun_terbit' => 2023, 'kategori' => 'Basis Data', 'lokasi_rak' => 'R-03', 'stok' => 4, 'cover_color' => 'cover-d', 'deskripsi' => 'Query tingkat lanjut, optimasi, dan administrasi basis data relasional.'],
            ['kode_buku' => 'BK-005', 'judul' => 'Laravel Untuk Pemula', 'penulis' => 'B. Pradana', 'penerbit' => 'Kode Media', 'tahun_terbit' => 2024, 'kategori' => 'Pemrograman Web', 'lokasi_rak' => 'R-05', 'stok' => 5, 'cover_color' => 'cover-a', 'deskripsi' => 'Belajar framework Laravel dari instalasi hingga deployment aplikasi web.'],
            ['kode_buku' => 'BK-006', 'judul' => 'JavaScript Modern', 'penulis' => 'S. Maulana', 'penerbit' => 'Akademika', 'tahun_terbit' => 2024, 'kategori' => 'Pemrograman Web', 'lokasi_rak' => 'R-05', 'stok' => 3, 'cover_color' => 'cover-b', 'deskripsi' => 'ES6+, DOM, dan pengembangan antarmuka web interaktif.'],
            ['kode_buku' => 'BK-007', 'judul' => 'Manajemen Proyek TI', 'penulis' => 'H. Kusuma', 'penerbit' => 'Lentera Press', 'tahun_terbit' => 2022, 'kategori' => 'Manajemen', 'lokasi_rak' => 'R-07', 'stok' => 2, 'cover_color' => 'cover-c', 'deskripsi' => 'Perencanaan, eksekusi, dan pengendalian proyek teknologi informasi.'],
            ['kode_buku' => 'BK-008', 'judul' => 'Jaringan Komputer Dasar', 'penulis' => 'T. Nugroho', 'penerbit' => 'Akademika', 'tahun_terbit' => 2021, 'kategori' => 'Jaringan Komputer', 'lokasi_rak' => 'R-08', 'stok' => 3, 'cover_color' => 'cover-d', 'deskripsi' => 'Topologi, protokol TCP/IP, dan konfigurasi jaringan lokal.'],
            ['kode_buku' => 'BK-009', 'judul' => 'Pengantar Machine Learning', 'penulis' => 'L. Anggraini', 'penerbit' => 'Kode Media', 'tahun_terbit' => 2024, 'kategori' => 'Kecerdasan Buatan', 'lokasi_rak' => 'R-09', 'stok' => 2, 'cover_color' => 'cover-a', 'deskripsi' => 'Konsep dasar pembelajaran mesin, supervised dan unsupervised learning.'],
            ['kode_buku' => 'BK-010', 'judul' => 'Literasi Digital', 'penulis' => 'M. Firdaus', 'penerbit' => 'Lentera Press', 'tahun_terbit' => 2023, 'kategori' => 'Umum', 'lokasi_rak' => 'R-10', 'stok' => 4, 'cover_color' => 'cover-b', 'deskripsi' => 'Keterampilan memanfaatkan teknologi digital secara bijak dan produktif.'],
        ];

        foreach ($buku as $item) {
            Buku::firstOrCreate(
                ['kode_buku' => $item['kode_buku']],
                [
                    'kategori_id' => $kategori[$item['kategori']] ?? null,
                    'judul' => $item['judul'],
                    'penulis' => $item['penulis'],
                    'penerbit' => $item['penerbit'],
                    'tahun_terbit' => $item['tahun_terbit'],
                    'deskripsi' => $item['deskripsi'],
                    'lokasi_rak' => $item['lokasi_rak'],
                    'stok_total' => $item['stok'],
                    'stok_tersedia' => $item['stok'],
                    'cover_color' => $item['cover_color'],
                    'status_buku' => 'tersedia',
                ]
            );
        }
    }
}
