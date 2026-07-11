@extends('layouts.admin')

@section('title', 'Cetak Kartu Anggota')
@section('eyebrow', 'Kartu Anggota')

@section('content')
<div class="split">
    <section class="card">
        <h3>Pilih Anggota</h3>
        <form method="GET" action="{{ route('admin.kartu.index') }}" style="margin-top:18px">
            <div class="field">
                <label for="q">Cari Anggota Aktif</label>
                <input id="q" type="text" name="q" value="{{ request('q') }}" placeholder="Nama, email, atau username">
            </div>
            <button type="submit" class="btn primary">Cari</button>
        </form>
        <div style="margin-top:18px">
            @if($hasil->isEmpty())
                <div class="empty-state"><p>Tidak ada anggota aktif yang cocok.</p></div>
            @else
                <x-table-wrap>
                    <thead>
                        <tr><th>ID</th><th>Nama</th><th>Aksi</th></tr>
                    </thead>
                    <tbody>
                        @foreach($hasil as $item)
                            <tr>
                                <td>{{ $item->kodeAnggota() }}</td>
                                <td>{{ $item->nama_lengkap }}</td>
                                <td><a class="action-link" href="{{ route('admin.kartu.index', ['anggota' => $item->id, 'q' => request('q')]) }}">Preview Kartu</a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </x-table-wrap>
                <div class="pagination-wrap">{{ $hasil->links() }}</div>
            @endif
        </div>
    </section>
    <section class="card">
        <h3>Preview Kartu</h3>
        @if($pilih)
            <div class="member-card-preview print-area" style="margin-top:18px">
                <x-brand />
                <h2>{{ $pilih->nama_lengkap }}</h2>
                <p>ID Anggota: {{ $pilih->kodeAnggota() }}</p>
                <p>Status: Terverifikasi · Sejak {{ $pilih->tanggal_daftar->translatedFormat('M Y') }}</p>
            </div>
            <div style="display:flex;gap:12px;margin-top:22px;flex-wrap:wrap">
                <a class="btn primary" href="{{ route('admin.kartu.cetak', $pilih) }}">Unduh PDF</a>
                <button type="button" class="btn" onclick="window.print()">Cetak Langsung</button>
            </div>
        @else
            <div class="empty-state" style="margin-top:18px">
                <h3>Belum ada anggota dipilih</h3>
                <p>Cari lalu pilih anggota untuk melihat preview kartu.</p>
            </div>
        @endif
    </section>
</div>
@endsection
