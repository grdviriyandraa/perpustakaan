@extends('layouts.admin')

@section('title', 'Kelola Peminjaman')
@section('eyebrow', 'Peminjaman')

@section('content')
<div class="tab-row">
    <a class="tab-link {{ $tab === 'menunggu' ? 'active' : '' }}" href="{{ route('admin.peminjaman.index', ['tab' => 'menunggu']) }}">Menunggu</a>
    <a class="tab-link {{ $tab === 'aktif' ? 'active' : '' }}" href="{{ route('admin.peminjaman.index', ['tab' => 'aktif']) }}">Aktif</a>
    <a class="tab-link {{ $tab === 'selesai' ? 'active' : '' }}" href="{{ route('admin.peminjaman.index', ['tab' => 'selesai']) }}">Selesai / Ditolak</a>
</div>

<section class="card">
    <div class="card-title"><h3>Daftar Pengajuan</h3></div>
    @if($peminjaman->isEmpty())
        <div class="empty-state">
            <h3>Tidak ada data</h3>
            <p>Tidak ada peminjaman pada tab ini.</p>
        </div>
    @else
        <x-table-wrap>
            <thead>
                <tr><th>Kode</th><th>Anggota</th><th>Buku</th><th>Tanggal Pengajuan</th><th>Jatuh Tempo</th><th>Status</th><th>Aksi</th></tr>
            </thead>
            <tbody>
                @foreach($peminjaman as $item)
                    <tr>
                        <td>{{ $item->kodePeminjaman() }}</td>
                        <td>{{ $item->anggota->nama_lengkap }}</td>
                        <td>{{ $item->detail->first()?->buku?->judul ?? '-' }}</td>
                        <td>{{ $item->tanggal_pengajuan->translatedFormat('d M Y') }}</td>
                        <td>
                            {{ $item->tanggal_jatuh_tempo?->translatedFormat('d M Y') ?? '-' }}
                            @if($item->isTerlambat())
                                <x-badge text="Terlambat" />
                            @endif
                        </td>
                        <td><x-badge :text="$item->statusLabel()" /></td>
                        <td><a class="action-link" href="{{ route('admin.peminjaman.show', $item) }}">Detail</a></td>
                    </tr>
                @endforeach
            </tbody>
        </x-table-wrap>
        <div class="pagination-wrap">{{ $peminjaman->links() }}</div>
    @endif
</section>
@endsection
