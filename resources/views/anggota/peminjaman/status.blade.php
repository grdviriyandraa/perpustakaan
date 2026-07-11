@extends('layouts.anggota')

@section('title', 'Status Peminjaman')
@section('eyebrow', 'Peminjaman')

@section('content')
<section class="card">
    <div class="card-title">
        <h3>Peminjaman Berjalan</h3>
        <a class="btn primary" href="{{ route('anggota.katalog') }}">Ajukan Peminjaman Baru</a>
    </div>
    @if($peminjaman->isEmpty())
        <div class="empty-state">
            <h3>Tidak ada peminjaman berjalan</h3>
            <p>Cari buku di katalog dan ajukan peminjaman.</p>
        </div>
    @else
        <x-table-wrap>
            <thead>
                <tr><th>Kode</th><th>Judul Buku</th><th>Tanggal Pengajuan</th><th>Tanggal Pinjam</th><th>Jatuh Tempo</th><th>Status</th></tr>
            </thead>
            <tbody>
                @foreach($peminjaman as $item)
                    <tr>
                        <td>{{ $item->kodePeminjaman() }}</td>
                        <td>{{ $item->detail->first()?->buku?->judul ?? '-' }}</td>
                        <td>{{ $item->tanggal_pengajuan->translatedFormat('d M Y') }}</td>
                        <td>{{ $item->tanggal_pinjam?->translatedFormat('d M Y') ?? '-' }}</td>
                        <td>
                            {{ $item->tanggal_jatuh_tempo?->translatedFormat('d M Y') ?? '-' }}
                            @if($item->isTerlambat())
                                <x-badge text="Terlambat {{ $item->hariTerlambat() }} hari" />
                            @endif
                        </td>
                        <td><x-badge :text="$item->statusLabel()" /></td>
                    </tr>
                @endforeach
            </tbody>
        </x-table-wrap>
        <p class="notice">Pengajuan berstatus <strong>Disetujui</strong> berarti buku siap diambil di perpustakaan.</p>
    @endif
</section>
@endsection
