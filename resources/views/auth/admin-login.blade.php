@extends('layouts.auth')

@section('title', 'Masuk Admin')

@section('content')
<div class="auth-layout">
    <div class="auth-hero">
        <x-brand />
        <div class="hero-copy">
            <p class="eyebrow">Panel Administrasi</p>
            <h1>Kelola perpustakaan dari satu tempat.</h1>
            <p>Masuk sebagai admin untuk memverifikasi anggota, memvalidasi peminjaman, mencatat pengembalian, dan menyusun laporan.</p>
        </div>
        <div class="book-illustration" aria-hidden="true">
            <div class="book-spine"></div>
            <div class="book-spine"></div>
            <div class="book-spine"></div>
        </div>
    </div>
    <section class="auth-card">
        <h2>Masuk Admin</h2>
        <p class="subcopy">Khusus petugas perpustakaan.</p>
        <x-alert />
        <form method="POST" action="{{ route('admin.login.submit') }}">
            @csrf
            <div class="field">
                <label for="login">Email atau username</label>
                <input id="login" type="text" name="login" value="{{ old('login') }}" required autofocus>
                <x-field-error name="login" />
            </div>
            <div class="field">
                <label for="password">Password</label>
                <input id="password" type="password" name="password" required>
                <x-field-error name="password" />
            </div>
            <button type="submit" class="btn primary" style="width:100%">Masuk</button>
        </form>
        <p style="margin-top:18px"><a class="link-button" href="{{ route('login') }}">Kembali ke login anggota</a></p>
    </section>
</div>
@endsection
