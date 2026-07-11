@extends('layouts.admin')

@section('title', 'Pengaturan Sistem')
@section('eyebrow', 'Pengaturan')

@section('content')
<section class="card">
    <h3>Konfigurasi Dasar</h3>
    <form method="POST" action="{{ route('admin.setting.update') }}">
        @csrf
        @method('PUT')
        <div class="settings-list" style="margin-top:12px">
            <div class="setting-row">
                <div>
                    <strong>{{ $settings['tarif_denda_harian']->label ?? 'Tarif Denda Harian' }}</strong>
                    <p>{{ $settings['tarif_denda_harian']->deskripsi ?? '' }}</p>
                </div>
                <div>
                    <input type="number" name="tarif_denda_harian" min="0" value="{{ old('tarif_denda_harian', $settings['tarif_denda_harian']->value ?? 5000) }}">
                    <x-field-error name="tarif_denda_harian" />
                </div>
            </div>
            <div class="setting-row">
                <div>
                    <strong>{{ $settings['durasi_peminjaman']->label ?? 'Durasi Peminjaman (hari)' }}</strong>
                    <p>{{ $settings['durasi_peminjaman']->deskripsi ?? '' }}</p>
                </div>
                <div>
                    <input type="number" name="durasi_peminjaman" min="1" max="90" value="{{ old('durasi_peminjaman', $settings['durasi_peminjaman']->value ?? 7) }}">
                    <x-field-error name="durasi_peminjaman" />
                </div>
            </div>
            <div class="setting-row">
                <div>
                    <strong>{{ $settings['batas_peminjaman']->label ?? 'Batas Jumlah Buku per Anggota' }}</strong>
                    <p>{{ $settings['batas_peminjaman']->deskripsi ?? '' }}</p>
                </div>
                <div>
                    <input type="number" name="batas_peminjaman" min="1" max="20" value="{{ old('batas_peminjaman', $settings['batas_peminjaman']->value ?? 3) }}">
                    <x-field-error name="batas_peminjaman" />
                </div>
            </div>
            <div class="setting-row">
                <div>
                    <strong>{{ $settings['verifikasi_akun']->label ?? 'Verifikasi Akun Wajib' }}</strong>
                    <p>{{ $settings['verifikasi_akun']->deskripsi ?? '' }}</p>
                </div>
                <div>
                    <select name="verifikasi_akun">
                        <option value="aktif" @selected(old('verifikasi_akun', $settings['verifikasi_akun']->value ?? 'aktif') === 'aktif')>Aktif</option>
                        <option value="nonaktif" @selected(old('verifikasi_akun', $settings['verifikasi_akun']->value ?? 'aktif') === 'nonaktif')>Nonaktif</option>
                    </select>
                    <x-field-error name="verifikasi_akun" />
                </div>
            </div>
        </div>
        <button type="submit" class="btn primary" style="margin-top:18px">Simpan Pengaturan</button>
    </form>
</section>
@endsection
