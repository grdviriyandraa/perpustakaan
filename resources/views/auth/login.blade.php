@extends('layouts.auth')

@section('title', 'Masuk')

@section('content')
<div class="auth-layout">
    <div class="auth-hero">
        <x-brand />
        <div class="hero-copy">
            <p class="eyebrow">Sistem Perpustakaan Digital</p>
            <h1>Kelola pinjaman buku dengan lebih rapi.</h1>
            <p>Masuk sebagai anggota untuk mengakses katalog, peminjaman, pengembalian, denda, dan riwayat pinjaman perpustakaan.</p>
        </div>
        <div class="book-illustration" aria-hidden="true">
            <div class="book-spine"></div>
            <div class="book-spine"></div>
            <div class="book-spine"></div>
        </div>
    </div>
    <section class="auth-card">
        <h2>Masuk</h2>
        <p class="subcopy">Gunakan akun yang sudah terdaftar dan terverifikasi.</p>
        <x-alert />
        <form method="POST" action="{{ route('login.submit') }}">
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
            <div class="field" style="grid-auto-flow:column;justify-content:start;align-items:center">
                <label style="display:flex;align-items:center;gap:8px;font-weight:500">
                    <input type="checkbox" name="remember" value="1" style="width:auto"> Ingat saya
                </label>
            </div>
            <button type="submit" class="btn primary" style="width:100%">Masuk</button>
        </form>
        <p style="margin-top:18px">Belum punya akun? <a class="link-button" href="{{ route('register') }}">Daftar anggota baru</a></p>
        <p style="margin-top:8px"><a class="link-button" href="{{ route('bantuan') }}">Lihat bantuan</a> · <a class="link-button" href="{{ route('admin.login') }}">Masuk sebagai admin</a></p>
    </section>
</div>
@endsection
