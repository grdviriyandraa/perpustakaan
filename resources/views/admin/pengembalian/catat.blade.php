@extends('layouts.admin')

@section('title', 'Pencatatan Pengembalian')
@section('eyebrow', 'Pengembalian')

@section('content')
<div class="split">
    <section class="card">
        <div class="card-title">
            <h3>Detail Peminjaman</h3>
            <a class="btn" href="{{ route('admin.pengembalian.index') }}">Kembali</a>
        </div>
        <div class="info-grid" style="grid-template-columns:1fr 1fr">
            <div class="info-item"><p>Kode Peminjaman</p><strong>{{ $peminjaman->kodePeminjaman() }}</strong></div>
            <div class="info-item"><p>Anggota</p><strong>{{ $peminjaman->anggota->nama_lengkap }}</strong></div>
            <div class="info-item"><p>Buku</p><strong>{{ $peminjaman->detail->first()?->buku?->judul ?? '-' }}</strong></div>
            <div class="info-item"><p>Tanggal Pinjam</p><strong>{{ $peminjaman->tanggal_pinjam?->translatedFormat('d F Y') }}</strong></div>
            <div class="info-item"><p>Jatuh Tempo</p><strong>{{ $peminjaman->tanggal_jatuh_tempo?->translatedFormat('d F Y') }}</strong></div>
            <div class="info-item">
                <p>Status Hari Ini</p>
                <strong>{{ $preview['terlambat'] > 0 ? "Terlambat {$preview['terlambat']} hari" : 'Belum jatuh tempo' }}</strong>
            </div>
        </div>
    </section>
    <section class="card">
        <h3>Catat Pengembalian</h3>
        <p style="margin:8px 0 18px">Denda dihitung otomatis berdasarkan tanggal jatuh tempo.</p>
        <form method="POST" action="{{ route('admin.pengembalian.store', $peminjaman) }}">
            @csrf
            <div class="field">
                <label for="tanggal_pengembalian">Tanggal Pengembalian</label>
                <input id="tanggal_pengembalian" type="date" name="tanggal_pengembalian"
                    value="{{ old('tanggal_pengembalian', now()->toDateString()) }}"
                    min="{{ $peminjaman->tanggal_pinjam?->toDateString() }}" required>
                <x-field-error name="tanggal_pengembalian" />
            </div>
            <div class="field">
                <label>Perkiraan Denda (jika dikembalikan hari ini)</label>
                <input type="text" value="{{ $preview['terlambat'] }} hari × {{ rupiah($preview['tarif']) }} = {{ rupiah($preview['total']) }}" disabled>
            </div>
            <button type="submit" class="btn primary" style="width:100%">Simpan Pengembalian</button>
        </form>
        <p class="notice">Denda final dihitung ulang dari tanggal pengembalian yang disimpan.</p>
    </section>
</div>
@endsection
