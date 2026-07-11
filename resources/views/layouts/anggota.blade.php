<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') — PerpusApp</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700;800&family=Playfair+Display:wght@700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
</head>
<body>
@php
    $anggota = auth('anggota')->user();
    $unread = $anggota->notifikasi()->where('is_read', false)->count();
    $menu = [
        ['Dashboard', 'anggota.dashboard', 'anggota.dashboard', 'DG'],
        ['Katalog Buku', 'anggota.katalog', 'anggota.katalog*', 'KB'],
        ['Peminjaman', 'anggota.peminjaman.status', 'anggota.peminjaman.status|anggota.peminjaman.form', 'PJ'],
        ['Riwayat', 'anggota.peminjaman.riwayat', 'anggota.peminjaman.riwayat', 'RW'],
        ['Denda', 'anggota.denda', 'anggota.denda', 'DN'],
        ['Notifikasi', 'anggota.notifikasi', 'anggota.notifikasi', 'NT'],
        ['Profil', 'anggota.profil', 'anggota.profil', 'PR'],
        ['Bantuan', 'anggota.bantuan', 'anggota.bantuan', 'BT'],
    ];
@endphp
<div class="app-shell">
    <div class="workspace">
        <aside class="sidebar">
            <x-brand />
            <nav class="side-menu" aria-label="Menu utama">
                @foreach($menu as [$label, $route, $pattern, $icon])
                    <a class="side-item {{ collect(explode('|', $pattern))->contains(fn ($p) => request()->routeIs($p)) ? 'active' : '' }}" href="{{ route($route) }}">
                        <span class="side-icon">{{ $icon }}</span>
                        <span>{{ $label }}</span>
                        @if($label === 'Notifikasi' && $unread > 0)
                            <span class="side-count">{{ $unread }}</span>
                        @endif
                    </a>
                @endforeach
            </nav>
            <div class="boundary-note">
                <strong>Anggota</strong>
                <p>{{ $anggota->nama_lengkap }}<br>{{ $anggota->kodeAnggota() }}</p>
            </div>
        </aside>
        <main class="main">
            <header class="topbar">
                <div>
                    <p class="eyebrow">@yield('eyebrow', 'PerpusApp')</p>
                    <h2>@yield('title')</h2>
                </div>
                <div class="top-actions">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn">Keluar</button>
                    </form>
                    <div class="avatar">{{ strtoupper(substr($anggota->nama_lengkap, 0, 1)) }}</div>
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
