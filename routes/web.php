<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\Anggota;
use App\Http\Controllers\Auth\AdminLoginController;
use App\Http\Controllers\Auth\AnggotaLoginController;
use App\Http\Controllers\Auth\AnggotaRegisterController;
use App\Http\Controllers\BantuanController;
use Illuminate\Support\Facades\Route;

// ─── Auth (publik) ───────────────────────────────────────────────
Route::get('/', [AnggotaLoginController::class, 'showLogin'])->name('login');
Route::post('/login', [AnggotaLoginController::class, 'login'])->name('login.submit');
Route::post('/logout', [AnggotaLoginController::class, 'logout'])->name('logout');
Route::get('/register', [AnggotaRegisterController::class, 'showForm'])->name('register');
Route::post('/register', [AnggotaRegisterController::class, 'store'])->name('register.submit');
Route::get('/bantuan', [BantuanController::class, 'publik'])->name('bantuan');

// ─── Admin Auth ──────────────────────────────────────────────────
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminLoginController::class, 'showLogin'])->name('login');
    Route::post('/login', [AdminLoginController::class, 'login'])->name('login.submit');
    Route::post('/logout', [AdminLoginController::class, 'logout'])->name('logout');
});

// ─── Anggota (protected) ─────────────────────────────────────────
Route::prefix('anggota')->name('anggota.')->middleware('auth.anggota')->group(function () {
    Route::get('/dashboard', [Anggota\DashboardController::class, 'index'])->name('dashboard');

    // Katalog
    Route::get('/katalog', [Anggota\BukuController::class, 'index'])->name('katalog');
    Route::get('/katalog/{buku}', [Anggota\BukuController::class, 'show'])->name('katalog.detail');

    // Peminjaman
    Route::get('/peminjaman/ajukan/{buku}', [Anggota\PeminjamanController::class, 'showForm'])->name('peminjaman.form');
    Route::post('/peminjaman', [Anggota\PeminjamanController::class, 'store'])->name('peminjaman.store');
    Route::get('/peminjaman/status', [Anggota\PeminjamanController::class, 'status'])->name('peminjaman.status');
    Route::get('/peminjaman/riwayat', [Anggota\PeminjamanController::class, 'riwayat'])->name('peminjaman.riwayat');

    // Denda
    Route::get('/denda', [Anggota\DendaController::class, 'index'])->name('denda');

    // Profil
    Route::get('/profil', [Anggota\ProfilController::class, 'index'])->name('profil');
    Route::put('/profil', [Anggota\ProfilController::class, 'update'])->name('profil.update');
    Route::put('/profil/password', [Anggota\ProfilController::class, 'updatePassword'])->name('profil.password');

    // Notifikasi
    Route::get('/notifikasi', [Anggota\NotifikasiController::class, 'index'])->name('notifikasi');
    Route::post('/notifikasi/baca', [Anggota\NotifikasiController::class, 'markAllRead'])->name('notifikasi.baca');

    // Wishlist
    Route::post('/wishlist/{buku}', [Anggota\WishlistController::class, 'toggle'])->name('wishlist.toggle');

    // Bantuan
    Route::get('/bantuan', [BantuanController::class, 'anggota'])->name('bantuan');
});

// ─── Admin (protected) ───────────────────────────────────────────
Route::prefix('admin')->name('admin.')->middleware('auth.admin')->group(function () {
    Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');

    // Buku
    Route::resource('buku', Admin\BukuController::class)->parameters(['buku' => 'buku'])->except(['show']);

    // Kategori
    Route::resource('kategori', Admin\KategoriBukuController::class)->only(['index', 'store', 'update', 'destroy']);

    // Anggota
    Route::get('/anggota', [Admin\AnggotaController::class, 'index'])->name('anggota.index');
    Route::get('/anggota/{anggota}', [Admin\AnggotaController::class, 'show'])->name('anggota.show');
    Route::patch('/anggota/{anggota}/status', [Admin\AnggotaController::class, 'updateStatus'])->name('anggota.status');

    // Peminjaman
    Route::get('/peminjaman', [Admin\PeminjamanController::class, 'index'])->name('peminjaman.index');
    Route::get('/peminjaman/{peminjaman}', [Admin\PeminjamanController::class, 'show'])->name('peminjaman.show');
    Route::patch('/peminjaman/{peminjaman}/validasi', [Admin\PeminjamanController::class, 'validasi'])->name('peminjaman.validasi');
    Route::patch('/peminjaman/{peminjaman}/konfirmasi', [Admin\PeminjamanController::class, 'konfirmasiAmbil'])->name('peminjaman.konfirmasi');

    // Pengembalian
    Route::get('/pengembalian', [Admin\PengembalianController::class, 'index'])->name('pengembalian.index');
    Route::get('/pengembalian/{peminjaman}/catat', [Admin\PengembalianController::class, 'form'])->name('pengembalian.form');
    Route::post('/pengembalian/{peminjaman}', [Admin\PengembalianController::class, 'store'])->name('pengembalian.store');

    // Denda
    Route::get('/denda', [Admin\DendaController::class, 'index'])->name('denda.index');
    Route::patch('/denda/{denda}/lunas', [Admin\DendaController::class, 'markLunas'])->name('denda.lunas');

    // Laporan
    Route::get('/laporan', [Admin\LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/pdf', [Admin\LaporanController::class, 'exportPdf'])->name('laporan.pdf');

    // Kartu Anggota
    Route::get('/kartu', [Admin\KartuAnggotaController::class, 'index'])->name('kartu.index');
    Route::get('/kartu/{anggota}', [Admin\KartuAnggotaController::class, 'cetak'])->name('kartu.cetak');

    // Setting
    Route::get('/setting', [Admin\SettingController::class, 'index'])->name('setting.index');
    Route::put('/setting', [Admin\SettingController::class, 'update'])->name('setting.update');

    // Notifikasi
    Route::get('/notifikasi', [Admin\NotifikasiController::class, 'index'])->name('notifikasi.index');
    Route::post('/notifikasi/baca', [Admin\NotifikasiController::class, 'markAllRead'])->name('notifikasi.baca');

    // Bantuan
    Route::get('/bantuan', [BantuanController::class, 'admin'])->name('bantuan');
});
