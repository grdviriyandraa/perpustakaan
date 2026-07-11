@extends('layouts.auth')

@section('title', 'Daftar Anggota')

@section('content')
<div class="auth-layout">
    <div class="auth-hero">
        <x-brand />
        <div class="hero-copy">
            <p class="eyebrow">Registrasi Anggota</p>
            <h1>Mulai akses katalog perpustakaan.</h1>
            <p>Data pendaftaran akan diperiksa oleh admin sebelum akun dapat digunakan untuk pengajuan peminjaman.</p>
        </div>
        <div class="book-illustration" aria-hidden="true">
            <div class="book-spine"></div>
            <div class="book-spine"></div>
            <div class="book-spine"></div>
        </div>
    </div>
    <section class="auth-card">
        <h2>Daftar Akun</h2>
        <p class="subcopy">Isi data utama agar formulir tetap ringan dan mudah dipahami.</p>
        <x-alert />
        <form method="POST" action="{{ route('register.submit') }}">
            @csrf
            <div class="form-grid">
                <div class="field">
                    <label for="nama_lengkap">Nama lengkap</label>
                    <input id="nama_lengkap" type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}" required>
                    <x-field-error name="nama_lengkap" />
                </div>
                <div class="field">
                    <label for="email">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required>
                    <x-field-error name="email" />
                </div>
                <div class="field">
                    <label for="no_telp">Nomor telepon</label>
                    <input id="no_telp" type="text" name="no_telp" value="{{ old('no_telp') }}">
                    <x-field-error name="no_telp" />
                </div>
                <div class="field">
                    <label for="username">Username</label>
                    <input id="username" type="text" name="username" value="{{ old('username') }}" required>
                    <x-field-error name="username" />
                </div>
                <div class="field">
                    <label for="password">Password</label>
                    <input id="password" type="password" name="password" required>
                    <x-field-error name="password" />
                </div>
                <div class="field">
                    <label for="password_confirmation">Konfirmasi password</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required>
                </div>
            </div>
            <div class="field">
                <label for="alamat">Alamat</label>
                <textarea id="alamat" name="alamat">{{ old('alamat') }}</textarea>
                <x-field-error name="alamat" />
            </div>
            <button type="submit" class="btn primary" style="width:100%">Kirim Registrasi</button>
        </form>
        <p class="notice">Setelah daftar, status akun menjadi <strong>Menunggu Verifikasi Admin</strong>.</p>
        <p style="margin-top:18px">Sudah punya akun? <a class="link-button" href="{{ route('login') }}">Masuk</a></p>
    </section>
</div>
@endsection
