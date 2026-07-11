<?php

namespace Database\Seeders;

use App\Models\Anggota;
use Illuminate\Database\Seeder;

class AnggotaSeeder extends Seeder
{
    public function run(): void
    {
        $anggota = [
            [
                'nama_lengkap' => 'Ryan Jasper',
                'username' => 'ryan',
                'email' => 'ryan@perpusapp.id',
                'password' => 'password',
                'no_telp' => '081234567890',
                'alamat' => 'Jl. Lentera No. 12',
                'status_akun' => 'aktif',
            ],
            [
                'nama_lengkap' => 'Nadia Ayu',
                'username' => 'nadia',
                'email' => 'nadia@perpusapp.id',
                'password' => 'password',
                'no_telp' => '081298765432',
                'alamat' => 'Jl. Cendekia No. 8',
                'status_akun' => 'menunggu',
            ],
        ];

        foreach ($anggota as $item) {
            Anggota::firstOrCreate(
                ['username' => $item['username']],
                [...$item, 'tanggal_daftar' => now()->toDateString()]
            );
        }
    }
}
