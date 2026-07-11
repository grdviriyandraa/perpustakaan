@extends('layouts.admin')

@section('title', 'Kategori Buku')
@section('eyebrow', 'Kategori')

@section('content')
<div class="split">
    <section class="card">
        <div class="card-title"><h3>Data Kategori</h3></div>
        @if($kategori->isEmpty())
            <div class="empty-state"><p>Belum ada kategori.</p></div>
        @else
            <x-table-wrap>
                <thead>
                    <tr><th>Kategori</th><th>Deskripsi</th><th>Jumlah Buku</th><th>Aksi</th></tr>
                </thead>
                <tbody>
                    @foreach($kategori as $item)
                        <tr>
                            <td>{{ $item->nama_kategori }}</td>
                            <td>{{ $item->deskripsi ?? '-' }}</td>
                            <td>{{ $item->buku_count }}</td>
                            <td>
                                <a class="action-link" href="{{ route('admin.kategori.index', ['edit' => $item->id]) }}">Ubah</a>
                                @if($item->buku_count === 0)
                                    |
                                    <form class="inline-form" method="POST" action="{{ route('admin.kategori.destroy', $item) }}" onsubmit="return confirm('Hapus kategori ini?')">
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
        @endif
    </section>
    <section class="card">
        <h3>{{ $edit ? 'Ubah Kategori' : 'Tambah Kategori' }}</h3>
        <form method="POST" action="{{ $edit ? route('admin.kategori.update', $edit) : route('admin.kategori.store') }}" style="margin-top:18px">
            @csrf
            @if($edit)
                @method('PUT')
            @endif
            <div class="field">
                <label for="nama_kategori">Nama Kategori</label>
                <input id="nama_kategori" type="text" name="nama_kategori" value="{{ old('nama_kategori', $edit->nama_kategori ?? '') }}" required>
                <x-field-error name="nama_kategori" />
            </div>
            <div class="field">
                <label for="deskripsi">Deskripsi</label>
                <textarea id="deskripsi" name="deskripsi">{{ old('deskripsi', $edit->deskripsi ?? '') }}</textarea>
                <x-field-error name="deskripsi" />
            </div>
            <button type="submit" class="btn primary" style="width:100%">{{ $edit ? 'Simpan Perubahan' : 'Simpan Kategori' }}</button>
            @if($edit)
                <a class="btn" href="{{ route('admin.kategori.index') }}" style="width:100%;margin-top:10px">Batal Ubah</a>
            @endif
        </form>
    </section>
</div>
@endsection
