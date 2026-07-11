@extends('layouts.admin')

@section('title', 'Bantuan dan FAQ')
@section('eyebrow', 'Bantuan')

@section('content')
<div class="grid cols-2">
    <section class="card">
        <h3>Panduan Admin</h3>
        <div class="notify-row">
            <div class="icon-box">A</div>
            <div><strong>Verifikasi anggota</strong><p>Periksa data pendaftaran di Kelola Anggota, lalu setujui atau tolak.</p></div>
        </div>
        <div class="notify-row">
            <div class="icon-box">B</div>
            <div><strong>Validasi peminjaman</strong><p>Periksa stok dan status anggota sebelum menyetujui pengajuan.</p></div>
        </div>
        <div class="notify-row">
            <div class="icon-box">C</div>
            <div><strong>Konfirmasi pengambilan</strong><p>Setelah disetujui, konfirmasi ketika anggota mengambil buku fisik.</p></div>
        </div>
        <div class="notify-row">
            <div class="icon-box">D</div>
            <div><strong>Catat pengembalian</strong><p>Sistem menghitung denda otomatis jika melewati jatuh tempo.</p></div>
        </div>
    </section>
    <section class="card">
        <h3>Operasional Lainnya</h3>
        <div class="notify-row">
            <div class="icon-box">E</div>
            <div><strong>Kelola denda</strong><p>Tandai lunas setelah anggota membayar di perpustakaan.</p></div>
        </div>
        <div class="notify-row">
            <div class="icon-box">F</div>
            <div><strong>Laporan</strong><p>Filter per periode, lihat buku populer, dan ekspor PDF.</p></div>
        </div>
        <div class="notify-row">
            <div class="icon-box">G</div>
            <div><strong>Cetak kartu anggota</strong><p>Cari anggota aktif lalu unduh kartu dalam format PDF.</p></div>
        </div>
        <div class="notify-row">
            <div class="icon-box">H</div>
            <div><strong>Pengaturan sistem</strong><p>Ubah tarif denda, durasi, batas pinjaman, dan mode verifikasi.</p></div>
        </div>
    </section>
</div>
@endsection
