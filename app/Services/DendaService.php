<?php

namespace App\Services;

use App\Models\Admin;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DendaService
{
    public function __construct(
        private NotifikasiService $notifikasi,
        private PeminjamanService $peminjamanService,
    ) {
    }

    /**
     * Hitung keterlambatan dan total denda tanpa menyimpan apa pun.
     *
     * @return array{terlambat: int, tarif: int, total: int}
     */
    public function hitung(Peminjaman $peminjaman, Carbon $tanggalKembali): array
    {
        $jatuhTempo = Carbon::parse($peminjaman->tanggal_jatuh_tempo)->startOfDay();
        $kembali = $tanggalKembali->copy()->startOfDay();

        $terlambat = $kembali->gt($jatuhTempo) ? (int) $jatuhTempo->diffInDays($kembali) : 0;
        $tarif = (int) setting('tarif_denda_harian', 5000);

        return [
            'terlambat' => $terlambat,
            'tarif' => $tarif,
            'total' => $terlambat * $tarif,
        ];
    }

    /**
     * Catat pengembalian, buat denda bila terlambat, kembalikan stok,
     * dan tandai peminjaman selesai.
     */
    public function catatPengembalian(Peminjaman $peminjaman, Carbon $tanggalKembali, Admin $admin): Pengembalian
    {
        $hasil = $this->hitung($peminjaman, $tanggalKembali);

        $pengembalian = DB::transaction(function () use ($peminjaman, $tanggalKembali, $admin, $hasil) {
            $pengembalian = Pengembalian::create([
                'peminjaman_id' => $peminjaman->id,
                'admin_id' => $admin->id,
                'tanggal_pengembalian' => $tanggalKembali->toDateString(),
                'status_pengembalian' => $hasil['terlambat'] > 0 ? 'terlambat' : 'tepat_waktu',
                'total_hari_terlambat' => $hasil['terlambat'],
            ]);

            if ($hasil['terlambat'] > 0) {
                $pengembalian->denda()->create([
                    'anggota_id' => $peminjaman->anggota_id,
                    'jumlah_hari_terlambat' => $hasil['terlambat'],
                    'tarif_per_hari' => $hasil['tarif'],
                    'total_denda' => $hasil['total'],
                    'status_bayar' => 'belum',
                ]);
            }

            $peminjaman->update(['status' => 'selesai']);
            $this->peminjamanService->kembalikanStok($peminjaman);

            return $pengembalian;
        });

        $judul = $peminjaman->detail->first()?->buku?->judul ?? 'buku';

        $pesan = $hasil['terlambat'] > 0
            ? "Pengembalian \"{$judul}\" dicatat. Terlambat {$hasil['terlambat']} hari, denda ".rupiah($hasil['total']).'.'
            : "Pengembalian \"{$judul}\" dicatat. Terima kasih sudah mengembalikan tepat waktu.";

        $this->notifikasi->kirimKeAnggota($peminjaman->anggota, 'pengembalian', $pesan);

        return $pengembalian;
    }
}
