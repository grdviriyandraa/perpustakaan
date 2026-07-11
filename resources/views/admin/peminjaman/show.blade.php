@extends('layouts.admin')

@section('title', 'Detail Peminjaman')
@section('eyebrow', 'Peminjaman')

@section('content')
<div class="split">
    <section class="card">
        <div class="card-title">
            <h3>{{ $peminjaman->kodePeminjaman() }}</h3>
            <a class="btn" href="{{ route('admin.peminjaman.index') }}">Kembali</a>
        </div>
        <x-badge :text="$peminjaman->statusLabel()" />
        <div class="info-grid" style="grid-template-columns:1fr 1fr">
            <div class="info-item"><p>Anggota</p><strong>{{ $peminjaman->anggota->nama_lengkap }}</strong></div>
            <div class="info-item"><p>Status Anggota</p><strong>{{ $peminjaman->anggota->status_akun === 'aktif' ? 'Terverifikasi' : ucfirst($peminjaman->anggota->status_akun) }}</strong></div>
            <div class="info-item"><p>Buku</p><strong>{{ $peminjaman->detail->first()?->buku?->judul ?? '-' }}</strong></div>
            <div class="info-item"><p>Stok Tersedia</p><strong>{{ $peminjaman->detail->first()?->buku?->stok_tersedia ?? '-' }} buku</strong></div>
            <div class="info-item"><p>Tanggal Pengajuan</p><strong>{{ $peminjaman->tanggal_pengajuan->translatedFormat('d F Y') }}</strong></div>
            <div class="info-item"><p>Tanggal Pinjam</p><strong>{{ $peminjaman->tanggal_pinjam?->translatedFormat('d F Y') ?? '-' }}</strong></div>
            <div class="info-item"><p>Jatuh Tempo</p><strong>{{ $peminjaman->tanggal_jatuh_tempo?->translatedFormat('d F Y') ?? '-' }}</strong></div>
            <div class="info-item"><p>Divalidasi Oleh</p><strong>{{ $peminjaman->admin?->nama_admin ?? '-' }}</strong></div>
        </div>
        @if($peminjaman->catatan)
            <p class="notice">Catatan: {{ $peminjaman->catatan }}</p>
        @endif
    </section>
    <section class="card">
        <h3>Tindakan</h3>
        <div style="margin-top:16px;display:grid;gap:12px">
            @if($peminjaman->status === 'menunggu')
                <form method="POST" action="{{ route('admin.peminjaman.validasi', $peminjaman) }}">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="action" value="setujui">
                    <button type="submit" class="btn ok" style="width:100%">Setujui Pengajuan</button>
                </form>
                <form method="POST" action="{{ route('admin.peminjaman.validasi', $peminjaman) }}">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="action" value="tolak">
                    <div class="field">
                        <label for="catatan">Catatan penolakan (opsional)</label>
                        <textarea id="catatan" name="catatan" placeholder="Alasan penolakan">{{ old('catatan') }}</textarea>
                        <x-field-error name="catatan" />
                    </div>
                    <button type="submit" class="btn warn" style="width:100%">Tolak Pengajuan</button>
                </form>
            @elseif($peminjaman->status === 'disetujui')
                <p>Pengajuan sudah disetujui. Konfirmasi ketika anggota mengambil buku di perpustakaan.</p>
                <form method="POST" action="{{ route('admin.peminjaman.konfirmasi', $peminjaman) }}">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn primary" style="width:100%">Konfirmasi Buku Diambil</button>
                </form>
            @elseif($peminjaman->status === 'dipinjam')
                <p>Buku sedang dipinjam. Catat pengembalian ketika buku dikembalikan.</p>
                <a class="btn primary" href="{{ route('admin.pengembalian.form', $peminjaman) }}" style="width:100%">Catat Pengembalian</a>
            @elseif($peminjaman->status === 'selesai')
                <p>Transaksi selesai pada {{ $peminjaman->pengembalian?->tanggal_pengembalian?->translatedFormat('d F Y') }}.</p>
                @if($peminjaman->pengembalian?->denda)
                    <p class="notice">Denda {{ rupiah($peminjaman->pengembalian->denda->total_denda) }} ({{ $peminjaman->pengembalian->denda->status_bayar === 'lunas' ? 'Lunas' : 'Belum dibayar' }}).</p>
                @endif
            @else
                <p>Pengajuan ini ditolak.</p>
            @endif
        </div>
    </section>
</div>
@endsection
