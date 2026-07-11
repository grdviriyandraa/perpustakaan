@extends('layouts.admin')

@section('title', 'Ubah Buku')
@section('eyebrow', 'Kelola Buku')

@section('content')
<section class="card">
    <div class="card-title">
        <h3>Ubah Data Buku — {{ $buku->kode_buku }}</h3>
        <a class="btn" href="{{ route('admin.buku.index') }}">Kembali</a>
    </div>
    <form method="POST" action="{{ route('admin.buku.update', $buku) }}">
        @csrf
        @method('PUT')
        @include('admin.buku.form')
        <button type="submit" class="btn primary">Simpan Perubahan</button>
    </form>
</section>
@endsection
