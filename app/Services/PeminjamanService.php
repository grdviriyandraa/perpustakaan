<?php

namespace App\Services;

use App\Models\Admin;
use App\Models\Anggota;
use App\Models\Buku;
use App\Models\Peminjaman;
use Illuminate\Support\Facades\DB;

class PeminjamanService
{
    public function __construct(private NotifikasiService $notifikasi)
    {
    }

    /**
     * Cek apakah anggota memenuhi syarat mengajukan peminjaman buku ini.
     *
     * @return array{bisa: bool, alasan: string|null}
     */
    public function bisaPinjam(Anggota $anggota, Buku $buku): array
    {
        if ($anggota->status_akun !== 'aktif') {
            return ['bisa' => false, 'alasan' => 'Akun Anda belum aktif.'];
        }

        if ($buku->status_buku === 'tidak_aktif') {
            return ['bisa' => false, 'alasan' => 'Buku ini tidak tersedia di katalog.'];
        }

        if ($buku->stok_tersedia < 1) {
            return ['bisa' => false, 'alasan' => 'Stok buku sedang habis.'];
        }

        $batas = (int) setting('batas_peminjaman', 3);
        $aktif = $anggota->peminjamanAktif()->count();

        if ($aktif >= $batas) {
            return ['bisa' => false, 'alasan' => "Anda sudah mencapai batas {$batas} peminjaman aktif."];
        }

        if ($anggota->dendaBelumBayar()->exists()) {
            return ['bisa' => false, 'alasan' => 'Anda masih memiliki denda yang belum dibayar.'];
        }

        $sudahMengajukan = $anggota->peminjamanAktif()
            ->whereHas('detail', fn ($q) => $q->where('buku_id', $buku->id))
            ->exists();

        if ($sudahMengajukan) {
            return ['bisa' => false, 'alasan' => 'Anda sudah memiliki pengajuan atau pinjaman aktif untuk buku ini.'];
        }

        return ['bisa' => true, 'alasan' => null];
    }

    /**
     * Buat pengajuan peminjaman baru dan kurangi stok (atomic).
     */
    public function ajukan(Anggota $anggota, Buku $buku): Peminjaman
    {
        $peminjaman = DB::transaction(function () use ($anggota, $buku) {
            $bukuTerkunci = Buku::lockForUpdate()->findOrFail($buku->id);

            if ($bukuTerkunci->stok_tersedia < 1) {
                throw new \RuntimeException('Stok buku sedang habis.');
            }

            $bukuTerkunci->decrement('stok_tersedia');
            $this->sinkronkanStatusBuku($bukuTerkunci->fresh());

            $peminjaman = Peminjaman::create([
                'anggota_id' => $anggota->id,
                'tanggal_pengajuan' => now()->toDateString(),
                'status' => 'menunggu',
            ]);

            $peminjaman->detail()->create([
                'buku_id' => $bukuTerkunci->id,
                'jumlah' => 1,
            ]);

            return $peminjaman;
        });

        $this->notifikasi->kirimKeAdmin(
            'peminjaman',
            "Pengajuan peminjaman baru dari {$anggota->nama_lengkap}: \"{$buku->judul}\" ({$peminjaman->kodePeminjaman()})."
        );

        return $peminjaman;
    }

    /**
     * Admin menyetujui pengajuan: set tanggal pinjam & jatuh tempo.
     */
    public function setujui(Peminjaman $peminjaman, Admin $admin): void
    {
        $durasi = (int) setting('durasi_peminjaman', 7);
        $tanggalPinjam = now()->startOfDay();

        $peminjaman->update([
            'admin_id' => $admin->id,
            'status' => 'disetujui',
            'tanggal_pinjam' => $tanggalPinjam->toDateString(),
            'tanggal_jatuh_tempo' => $tanggalPinjam->copy()->addDays($durasi)->toDateString(),
        ]);

        $judul = $peminjaman->detail->first()?->buku?->judul ?? 'buku';

        $this->notifikasi->kirimKeAnggota(
            $peminjaman->anggota,
            'peminjaman',
            "Pengajuan {$peminjaman->kodePeminjaman()} (\"{$judul}\") disetujui. Silakan ambil buku di perpustakaan."
        );
    }

    /**
     * Admin menolak pengajuan: kembalikan stok.
     */
    public function tolak(Peminjaman $peminjaman, Admin $admin, ?string $catatan = null): void
    {
        DB::transaction(function () use ($peminjaman, $admin, $catatan) {
            $peminjaman->update([
                'admin_id' => $admin->id,
                'status' => 'ditolak',
                'catatan' => $catatan,
            ]);

            $this->kembalikanStok($peminjaman);
        });

        $judul = $peminjaman->detail->first()?->buku?->judul ?? 'buku';

        $this->notifikasi->kirimKeAnggota(
            $peminjaman->anggota,
            'peminjaman',
            "Pengajuan {$peminjaman->kodePeminjaman()} (\"{$judul}\") ditolak.".($catatan ? " Catatan: {$catatan}" : '')
        );
    }

    /**
     * Admin konfirmasi buku sudah diambil anggota.
     */
    public function konfirmasiAmbil(Peminjaman $peminjaman): void
    {
        $peminjaman->update(['status' => 'dipinjam']);

        $judul = $peminjaman->detail->first()?->buku?->judul ?? 'buku';
        $jatuhTempo = $peminjaman->tanggal_jatuh_tempo?->translatedFormat('d F Y');

        $this->notifikasi->kirimKeAnggota(
            $peminjaman->anggota,
            'peminjaman',
            "Buku \"{$judul}\" telah diambil. Jatuh tempo pengembalian: {$jatuhTempo}."
        );
    }

    /**
     * Kembalikan stok semua buku dalam transaksi peminjaman (dipakai saat tolak/pengembalian).
     */
    public function kembalikanStok(Peminjaman $peminjaman): void
    {
        foreach ($peminjaman->detail as $detail) {
            $buku = Buku::lockForUpdate()->find($detail->buku_id);

            if ($buku === null) {
                continue;
            }

            $buku->stok_tersedia = min($buku->stok_total, $buku->stok_tersedia + $detail->jumlah);
            $buku->save();
            $this->sinkronkanStatusBuku($buku);
        }
    }

    private function sinkronkanStatusBuku(Buku $buku): void
    {
        if ($buku->status_buku === 'tidak_aktif') {
            return;
        }

        $buku->update(['status_buku' => $buku->stok_tersedia > 0 ? 'tersedia' : 'dipinjam']);
    }
}
