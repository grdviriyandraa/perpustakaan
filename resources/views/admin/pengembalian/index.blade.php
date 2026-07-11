@extends('layouts.admin')

@section('title', 'Kelola Pengembalian')
@section('eyebrow', 'Pengembalian')

@section('content')
<div class="grid cols-3">
    <x-stat-card label="Peminjaman Aktif" :value="$totalAktif" icon="PA" />
    <x-stat-card label="Terlambat" :value="$totalTerlambat" icon="TL" />
    <x-stat-card label="Denda Tercatat Hari Ini" :value="rupiah($dendaHariIni)" icon="Rp" />
</div>

<section class="card" style="margin-top:22px">
    <div class="card-title"><h3>Peminjaman Berjalan</h3></div>
    @if($peminjaman->isEmpty())
        <div class="empty-state">
            <h3>Tidak ada peminjaman aktif</h3>
            <p>Semua buku sudah dikembalikan.</p>
        </div>
    @else
        <x-table-wrap>
            <thead>
                <tr><th>Kode</th><th>Anggota</th><th>Buku</th><th>Jatuh Tempo</th><th>Keterlambatan</th><th>Aksi</th></tr>
            </thead>
            <tbody>
                @foreach($peminjaman as $item)
                    <tr>
                        <td>{{ $item->kodePeminjaman() }}</td>
                        <td>{{ $item->anggota->nama_lengkap }}</td>
                        <td>{{ $item->detail->first()?->buku?->judul ?? '-' }}</td>
                        <td>{{ $item->tanggal_jatuh_tempo?->translatedFormat('d M Y') ?? '-' }}</td>
                        <td>
                            @if($item->isTerlambat())
                                <x-badge text="Terlambat {{ $item->hariTerlambat() }} hari" />
                            @else
                                -
                            @endif
                        </td>
                        <td><a class="action-link" href="{{ route('admin.pengembalian.form', $item) }}">Catat</a></td>
                    </tr>
                @endforeach
            </tbody>
        </x-table-wrap>
        <div class="pagination-wrap">{{ $peminjaman->links() }}</div>
    @endif
</section>
@endsection
