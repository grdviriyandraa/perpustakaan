@extends('layouts.anggota')

@section('title', 'Katalog dan Pencarian Buku')
@section('eyebrow', 'Katalog Buku')

@section('content')
<section class="card">
    <div class="toolbar">
        <div>
            <h3>Katalog Buku</h3>
            <p>Gunakan pencarian dan filter untuk menemukan buku yang tersedia.</p>
        </div>
        <a class="btn" href="{{ route('anggota.katalog') }}">Reset Filter</a>
    </div>
    <form method="GET" action="{{ route('anggota.katalog') }}">
        <div class="filters">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari judul, pengarang, atau kode buku">
            <select name="kategori" onchange="this.form.submit()">
                <option value="">Semua Kategori</option>
                @foreach($kategori as $item)
                    <option value="{{ $item->id }}" @selected(request('kategori') == $item->id)>{{ $item->nama_kategori }}</option>
                @endforeach
            </select>
            <select name="status" onchange="this.form.submit()">
                <option value="">Semua Status</option>
                <option value="tersedia" @selected(request('status') === 'tersedia')>Tersedia</option>
                <option value="habis" @selected(request('status') === 'habis')>Stok Habis</option>
            </select>
        </div>
    </form>
</section>

<div style="margin-top:20px">
    @if($buku->isEmpty())
        <div class="empty-state">
            <h3>Tidak ada buku ditemukan</h3>
            <p>Coba ubah kata kunci pencarian atau filter kategori.</p>
        </div>
    @else
        <div class="book-grid">
            @foreach($buku as $item)
                <x-book-card :buku="$item" />
            @endforeach
        </div>
        <div class="pagination-wrap">{{ $buku->links() }}</div>
    @endif
</div>
@endsection
