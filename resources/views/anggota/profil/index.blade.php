@extends('layouts.anggota')

@section('title', 'Profil Anggota')
@section('eyebrow', 'Profil')

@section('content')
<div class="split">
    <section class="card">
        <h3>Data Profil</h3>
        <p style="margin-top:6px">ID Anggota: <strong>{{ $anggota->kodeAnggota() }}</strong> · Status: <x-badge :text="ucfirst($anggota->status_akun)" /></p>
        <form method="POST" action="{{ route('anggota.profil.update') }}" style="margin-top:18px">
            @csrf
            @method('PUT')
            <div class="form-grid">
                <div class="field">
                    <label for="nama_lengkap">Nama</label>
                    <input id="nama_lengkap" type="text" name="nama_lengkap" value="{{ old('nama_lengkap', $anggota->nama_lengkap) }}" required>
                    <x-field-error name="nama_lengkap" />
                </div>
                <div class="field">
                    <label for="email">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email', $anggota->email) }}" required>
                    <x-field-error name="email" />
                </div>
                <div class="field">
                    <label for="no_telp">Nomor Telepon</label>
                    <input id="no_telp" type="text" name="no_telp" value="{{ old('no_telp', $anggota->no_telp) }}">
                    <x-field-error name="no_telp" />
                </div>
                <div class="field">
                    <label>Username</label>
                    <input type="text" value="{{ $anggota->username }}" disabled>
                </div>
            </div>
            <div class="field">
                <label for="alamat">Alamat</label>
                <textarea id="alamat" name="alamat">{{ old('alamat', $anggota->alamat) }}</textarea>
                <x-field-error name="alamat" />
            </div>
            <button type="submit" class="btn primary">Simpan Profil</button>
        </form>
    </section>
    <section class="card">
        <h3>Keamanan Akun</h3>
        <form method="POST" action="{{ route('anggota.profil.password') }}" style="margin-top:18px">
            @csrf
            @method('PUT')
            <div class="field">
                <label for="password_lama">Password Lama</label>
                <input id="password_lama" type="password" name="password_lama" required>
                <x-field-error name="password_lama" />
            </div>
            <div class="field">
                <label for="password">Password Baru</label>
                <input id="password" type="password" name="password" required>
                <x-field-error name="password" />
            </div>
            <div class="field">
                <label for="password_confirmation">Konfirmasi Password Baru</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required>
            </div>
            <button type="submit" class="btn">Ubah Password</button>
        </form>
        <div style="border-top:1px solid var(--line);margin:26px 0 18px"></div>
        <p>Terdaftar sejak {{ $anggota->tanggal_daftar->translatedFormat('d F Y') }}.</p>
    </section>
</div>
@endsection
