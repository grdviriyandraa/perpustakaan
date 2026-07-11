<?php

namespace App\Services;

use App\Models\Admin;
use App\Models\Anggota;
use App\Models\Notifikasi;

class NotifikasiService
{
    public function kirimKeAnggota(Anggota $anggota, string $tipe, string $pesan): void
    {
        Notifikasi::create([
            'anggota_id' => $anggota->id,
            'tipe' => $tipe,
            'pesan' => $pesan,
        ]);
    }

    public function kirimKeAdmin(string $tipe, string $pesan): void
    {
        foreach (Admin::pluck('id') as $adminId) {
            Notifikasi::create([
                'admin_id' => $adminId,
                'tipe' => $tipe,
                'pesan' => $pesan,
            ]);
        }
    }
}
