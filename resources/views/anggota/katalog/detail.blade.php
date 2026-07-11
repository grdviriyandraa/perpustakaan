@extends('layouts.anggota')

@section('title', 'Detail Buku')
@section('eyebrow', 'Katalog Buku')

@section('content')
<div class="split">
    <section class="card detail-panel">
        <div class="book-cover detail-cover {{ $buku->cover_color }}">
            <span>{{ $buku->judul }}</span>
            <small>{{ $buku->kategori?->nama_kategori ?? 'Umum' }}</small>
        </div>
        <div>
            <x-badge :text="$buku->statusLabel()" />
            <h2 style="margin:18px 0 12px">{{ $buku->judul }}</h2>
            <p>{{ $buku->deskripsi ?? 'Belum ada deskripsi untuk buku ini.' }}</p>
            <div class="info-grid">
                <div class="info-item"><p>Pengarang</p><strong>{{ $buku->penulis }}</strong></div>
                <div class="info-item"><p>Penerbit</p><strong>{{ $buku->penerbit ?? '-' }}</strong></div>
                <div class="info-item"><p>Kategori</p><strong>{{ $buku->kategori?->nama_kategori ?? '-' }}</strong></div>
                <div class="info-item"><p>Lokasi Rak</p><strong>{{ $buku->lokasi_rak ?? '-' }}</strong></div>
                <div class="info-item"><p>Stok</p><strong>{{ $buku->stok_tersedia }} tersedia</strong></div>
                <div class="info-item"><p>Tahun Terbit</p><strong>{{ $buku->tahun_terbit ?? '-' }}</strong></div>
            </div>
            <div style="display:flex;gap:12px;flex-wrap:wrap">
                @if($cek['bisa'])
                    <a class="btn primary" href="{{ route('anggota.peminjaman.form', $buku) }}">Ajukan Peminjaman</a>
                @else
                    <button class="btn" disabled>{{ $cek['alasan'] }}</button>
                @endif
                <form method="POST" action="{{ route('anggota.wishlist.toggle', $buku) }}">
                    @csrf
                    <button type="submit" class="btn {{ $diWishlist ? 'warn' : 'secondary' }}">
                        {{ $diWishlist ? 'Hapus dari Wishlist' : 'Tambah ke Wishlist' }}
                    </button>
                </form>
            </div>
        </div>
    </section>
    <section class="card">
        <div class="card-title"><h3>Buku Serupa</h3></div>
        @if($serupa->isEmpty())
            <div class="empty-state"><p>Tidak ada buku serupa di kategori ini.</p></div>
        @else
            <div class="book-grid" style="grid-template-columns:repeat(2,minmax(0,1fr))">
                @foreach($serupa as $item)
                    <x-book-card :buku="$item" />
                @endforeach
            </div>
        @endif
    </section>
</div>
@endsection
