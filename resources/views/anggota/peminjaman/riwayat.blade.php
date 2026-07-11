@extends('layouts.anggota')

@section('title', 'Riwayat Peminjaman')
@section('eyebrow', 'Riwayat')

@section('content')
<section class="card">
    <div class="card-title">
        <h3>Riwayat Peminjaman</h3>
    </div>
    @if($peminjaman->isEmpty())
        <div class="empty-state">
            <h3>Belum ada riwayat</h3>
            <p>Semua transaksi peminjaman Anda akan tercatat di sini.</p>
        </div>
    @else
        <x-table-wrap>
            <thead>
                <tr><th>Kode</th><th>Judul Buku</th><th>Tanggal Pinjam</th><th>Tanggal Kembali</th><th>Status</th></tr>
            </thead>
            <tbody>
                @foreach($peminjaman as $item)
                    <tr>
                        <td>{{ $item->kodePeminjaman() }}</td>
                        <td>{{ $item->detail->first()?->buku?->judul ?? '-' }}</td>
                        <td>{{ $item->tanggal_pinjam?->translatedFormat('d M Y') ?? '-' }}</td>
                        <td>{{ $item->pengembalian?->tanggal_pengembalian?->translatedFormat('d M Y') ?? '-' }}</td>
                        <td><x-badge :text="$item->statusLabel()" /></td>
                    </tr>
                @endforeach
            </tbody>
        </x-table-wrap>
        <div class="pagination-wrap">{{ $peminjaman->links() }}</div>
    @endif
</section>
@endsection
