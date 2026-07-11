@extends('layouts.auth')

@section('title', 'Bantuan')

@section('content')
<div style="padding:48px 64px">
    <div style="display:flex;justify-content:space-between;align-items:center;gap:18px;flex-wrap:wrap;margin-bottom:28px">
        <x-brand />
        <div style="display:flex;gap:10px">
            <a class="btn" href="{{ route('login') }}">Masuk</a>
            <a class="btn primary" href="{{ route('register') }}">Daftar</a>
        </div>
    </div>
    <p class="eyebrow">Bantuan dan FAQ</p>
    <h2 style="margin-bottom:22px">Panduan Penggunaan PerpusApp</h2>
    @include('bantuan.panduan-anggota')
</div>
@endsection
