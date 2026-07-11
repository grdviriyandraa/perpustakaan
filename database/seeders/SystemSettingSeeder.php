<?php

namespace Database\Seeders;

use App\Models\SystemSetting;
use Illuminate\Database\Seeder;

class SystemSettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            ['key' => 'tarif_denda_harian', 'value' => '5000', 'label' => 'Tarif Denda Harian', 'deskripsi' => 'Nominal denda untuk setiap hari keterlambatan (Rp).'],
            ['key' => 'durasi_peminjaman', 'value' => '7', 'label' => 'Durasi Peminjaman (hari)', 'deskripsi' => 'Jumlah hari peminjaman sebelum jatuh tempo.'],
            ['key' => 'batas_peminjaman', 'value' => '3', 'label' => 'Batas Jumlah Buku per Anggota', 'deskripsi' => 'Maksimum buku aktif (status dipinjam) per anggota.'],
            ['key' => 'verifikasi_akun', 'value' => 'aktif', 'label' => 'Verifikasi Akun Wajib', 'deskripsi' => 'Aktif = akun baru harus disetujui admin. Nonaktif = langsung aktif.'],
        ];

        foreach ($settings as $setting) {
            SystemSetting::updateOrCreate(['key' => $setting['key']], $setting);
        }
    }
}
