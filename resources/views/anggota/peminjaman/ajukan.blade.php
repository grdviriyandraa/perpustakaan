@extends('layouts.anggota')

@section('title', 'Pengajuan Peminjaman')
@section('eyebrow', 'Peminjaman')

@section('content')
<div class="split">
    <section class="card">
        <h3>Konfirmasi Pengajuan</h3>
        <div class="detail-panel" style="grid-template-columns:160px 1fr;margin-top:18px">
            <div class="book-cover {{ $buku->cover_color }}" style="min-height:220px">
                <span>{{ $buku->judul }}</span>
                <small>{{ $buku->kategori?->nama_kategori ?? 'Umum' }}</small>
            </div>
            <div>
                <p>Buku yang dipilih</p>
                <h2>{{ $buku->judul }}</h2>
                <p style="margin-top:12px">Pengajuan akan masuk ke daftar validasi admin. Buku fisik tetap harus diambil langsung di perpustakaan setelah disetujui.</p>
                <div style="display:flex;gap:12px;margin-top:22px;flex-wrap:wrap">
                    @if($cek['bisa'])
                        <form method="POST" action="{{ route('anggota.peminjaman.store') }}">
                            @csrf
                            <input type="hidden" name="buku_id" value="{{ $buku->id }}">
                            <button type="submit" class="btn primary">Ajukan Peminjaman</button>
                        </form>
                    @else
                        <button class="btn" disabled>Tidak Memenuhi Syarat</button>
                    @endif
                    <a class="btn" href="{{ route('anggota.katalog.detail', $buku) }}">Batal</a>
                </div>
            </div>
        </div>
    </section>
    <section class="card">
        <h3>Ringkasan Syarat</h3>
        <div class="timeline" style="margin-top:14px">
            <div class="timeline-row">
                <div class="icon-box">1</div>
                <div><strong>Status anggota aktif</strong><p>Akun sudah terverifikasi admin.</p></div>
                <x-badge :text="auth('anggota')->user()->status_akun === 'aktif' ? 'Memenuhi' : 'Belum'" />
            </div>
            <div class="timeline-row">
                <div class="icon-box">2</div>
                <div><strong>Stok tersedia</strong><p>Buku masih dapat diajukan.</p></div>
                <x-badge :text="$buku->stok_tersedia > 0 ? 'Tersedia' : 'Habis'" />
            </div>
            <div class="timeline-row">
                <div class="icon-box">3</div>
                <div><strong>Syarat lainnya</strong><p>Batas pinjaman dan denda.</p></div>
                <x-badge :text="$cek['bisa'] ? 'Memenuhi' : 'Belum'" />
            </div>
        </div>
        @unless($cek['bisa'])
            <p class="notice error">{{ $cek['alasan'] }}</p>
        @endunless
        <p class="notice">Durasi peminjaman {{ setting('durasi_peminjaman', 7) }} hari sejak disetujui. Keterlambatan dikenakan denda {{ rupiah((int) setting('tarif_denda_harian', 5000)) }}/hari.</p>
    </section>
</div>
@endsection
