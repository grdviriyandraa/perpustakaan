@extends('layouts.admin')

@section('title', 'Kelola Data Buku')
@section('eyebrow', 'Kelola Buku')

@section('content')
<section class="card">
    <div class="toolbar">
        <div>
            <h3>Daftar Buku</h3>
            <p>Kelola data buku, stok, kategori, dan lokasi rak.</p>
        </div>
        <a class="btn primary" href="{{ route('admin.buku.create') }}">Tambah Buku</a>
    </div>
    <form method="GET" action="{{ route('admin.buku.index') }}">
        <div class="filters" style="margin-bottom:16px">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari judul, penulis, atau kode buku">
            <select name="kategori" onchange="this.form.submit()">
                <option value="">Semua Kategori</option>
                @foreach($kategori as $item)
                    <option value="{{ $item->id }}" @selected(request('kategori') == $item->id)>{{ $item->nama_kategori }}</option>
                @endforeach
            </select>
            <select name="status" onchange="this.form.submit()">
                <option value="">Semua Status</option>
                <option value="tersedia" @selected(request('status') === 'tersedia')>Tersedia</option>
                <option value="dipinjam" @selected(request('status') === 'dipinjam')>Dipinjam</option>
                <option value="tidak_aktif" @selected(request('status') === 'tidak_aktif')>Tidak Aktif</option>
            </select>
        </div>
    </form>
    @if($buku->isEmpty())
        <div class="empty-state">
            <h3>Tidak ada buku</h3>
            <p>Tambahkan buku pertama ke katalog perpustakaan.</p>
        </div>
    @else
        <x-table-wrap>
            <thead>
                <tr><th>Kode</th><th>Judul</th><th>Kategori</th><th>Stok</th><th>Rak</th><th>Status</th><th>Aksi</th></tr>
            </thead>
            <tbody>
                @foreach($buku as $item)
                    <tr>
                        <td>{{ $item->kode_buku }}</td>
                        <td>{{ $item->judul }}<br><small style="color:var(--muted)">{{ $item->penulis }}</small></td>
                        <td>{{ $item->kategori?->nama_kategori ?? '-' }}</td>
                        <td>{{ $item->stok_tersedia }}/{{ $item->stok_total }}</td>
                        <td>{{ $item->lokasi_rak ?? '-' }}</td>
                        <td><x-badge :text="$item->status_buku === 'tidak_aktif' ? 'Nonaktif' : $item->statusLabel()" /></td>
                        <td>
                            <a class="action-link" href="{{ route('admin.buku.edit', $item) }}">Ubah</a>
                            @if($item->status_buku !== 'tidak_aktif')
                                |
                                <form class="inline-form" method="POST" action="{{ route('admin.buku.destroy', $item) }}" onsubmit="return confirm('Nonaktifkan buku ini dari katalog?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="action-link" style="color:var(--red)">Hapus</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </x-table-wrap>
        <div class="pagination-wrap">{{ $buku->links() }}</div>
    @endif
</section>
@endsection
