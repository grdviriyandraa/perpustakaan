@extends('layouts.anggota')

@section('title', 'Dashboard Anggota')
@section('eyebrow', 'Dashboard')

@section('content')
<div class="grid cols-3">
    <x-stat-card label="Pinjaman Aktif" :value="$pinjamanAktif" icon="PJ" />
    <x-stat-card label="Menunggu Validasi" :value="$menungguValidasi" icon="MV" />
    <x-stat-card label="Denda Aktif" :value="rupiah($totalDenda)" icon="DN" />
</div>

<div class="split" style="margin-top:22px">
    <section class="card">
        <div class="card-title">
            <h3>Cari Koleksi Buku</h3>
            <a class="btn primary" href="{{ route('anggota.katalog') }}">Cari Buku</a>
        </div>
        <form method="GET" action="{{ route('anggota.katalog') }}">
            <div class="field">
                <label for="q">Pencarian cepat</label>
                <input id="q" type="text" name="q" placeholder="Cari judul, pengarang, atau kode buku">
            </div>
        </form>
        <div class="book-grid" style="grid-template-columns:repeat(2,minmax(0,1fr))">
            @foreach($rekomendasi as $item)
                <x-book-card :buku="$item" />
            @endforeach
        </div>
    </section>
    <section class="card">
        <h3>Status Terbaru</h3>
        <div style="margin-top:16px">
            @if($terbaru->isEmpty())
                <div class="empty-state"><p>Belum ada transaksi peminjaman.</p></div>
            @else
                <x-table-wrap>
                    <thead>
                        <tr><th>Buku</th><th>Status</th><th>Jatuh Tempo</th></tr>
                    </thead>
                    <tbody>
                        @foreach($terbaru as $item)
                            <tr>
                                <td>{{ $item->detail->first()?->buku?->judul ?? '-' }}</td>
                                <td><x-badge :text="$item->statusLabel()" /></td>
                                <td>{{ $item->tanggal_jatuh_tempo?->translatedFormat('d M Y') ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </x-table-wrap>
            @endif
        </div>
    </section>
</div>
@endsection
