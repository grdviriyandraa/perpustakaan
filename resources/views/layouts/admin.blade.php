<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') — PerpusApp Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700;800&family=Playfair+Display:wght@700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
</head>
<body>
@php
    $admin = auth('admin')->user();
    $unread = $admin->notifikasi()->where('is_read', false)->count();
    $menu = [
        ['Dashboard', 'admin.dashboard', 'admin.dashboard', 'DG'],
        ['Kelola Buku', 'admin.buku.index', 'admin.buku.*', 'BK'],
        ['Kategori Buku', 'admin.kategori.index', 'admin.kategori.*', 'KT'],
        ['Kelola Anggota', 'admin.anggota.index', 'admin.anggota.*', 'AG'],
        ['Kelola Peminjaman', 'admin.peminjaman.index', 'admin.peminjaman.*', 'PJ'],
        ['Kelola Pengembalian', 'admin.pengembalian.index', 'admin.pengembalian.*', 'PB'],
        ['Denda', 'admin.denda.index', 'admin.denda.*', 'DN'],
        ['Laporan', 'admin.laporan.index', 'admin.laporan.*', 'LP'],
        ['Cetak Kartu', 'admin.kartu.index', 'admin.kartu.*', 'CK'],
        ['Pengaturan', 'admin.setting.index', 'admin.setting.*', 'ST'],
        ['Notifikasi', 'admin.notifikasi.index', 'admin.notifikasi.*', 'NT'],
        ['Bantuan', 'admin.bantuan', 'admin.bantuan', 'BT'],
    ];
@endphp
<div class="app-shell">
    <div class="workspace">
        <aside class="sidebar">
            <x-brand />
            <nav class="side-menu" aria-label="Menu utama">
                @foreach($menu as [$label, $route, $pattern, $icon])
                    <a class="side-item {{ request()->routeIs($pattern) ? 'active' : '' }}" href="{{ route($route) }}">
                        <span class="side-icon">{{ $icon }}</span>
                        <span>{{ $label }}</span>
                        @if($label === 'Notifikasi' && $unread > 0)
                            <span class="side-count">{{ $unread }}</span>
                        @endif
                    </a>
                @endforeach
            </nav>
            <div class="boundary-note">
                <strong>Admin</strong>
                <p>{{ $admin->nama_admin }}</p>
            </div>
        </aside>
        <main class="main">
            <header class="topbar">
                <div>
                    <p class="eyebrow">@yield('eyebrow', 'Panel Admin')</p>
                    <h2>@yield('title')</h2>
                </div>
                <div class="top-actions">
                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        <button type="submit" class="btn">Keluar</button>
                    </form>
                    <div class="avatar">{{ strtoupper(substr($admin->nama_admin, 0, 1)) }}</div>
                </div>
            </header>
            <x-alert />
            @yield('content')
        </main>
    </div>
</div>
@stack('scripts')
</body>
</html>
