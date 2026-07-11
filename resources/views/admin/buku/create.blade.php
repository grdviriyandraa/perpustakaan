@extends('layouts.admin')

@section('title', 'Tambah Buku')
@section('eyebrow', 'Kelola Buku')

@section('content')
<section class="card">
    <div class="card-title">
        <h3>Tambah Buku Baru</h3>
        <a class="btn" href="{{ route('admin.buku.index') }}">Kembali</a>
    </div>
    <form method="POST" action="{{ route('admin.buku.store') }}">
        @csrf
        @include('admin.buku.form', ['buku' => null])
        <button type="submit" class="btn primary">Simpan Buku</button>
    </form>
</section>
@endsection
