@extends('layouts.admin')

@section('title', 'Notifikasi')
@section('eyebrow', 'Notifikasi')

@section('content')
<section class="card">
    <div class="card-title">
        <h3>Daftar Notifikasi</h3>
        <form method="POST" action="{{ route('admin.notifikasi.baca') }}">
            @csrf
            <button type="submit" class="btn">Tandai Semua Dibaca</button>
        </form>
    </div>
    @if($notifikasi->isEmpty())
        <div class="empty-state">
            <h3>Belum ada notifikasi</h3>
            <p>Pendaftaran anggota baru dan pengajuan peminjaman akan muncul di sini.</p>
        </div>
    @else
        @foreach($notifikasi as $item)
            <div class="notify-row {{ $item->is_read ? '' : 'unread' }}">
                <div class="icon-box">{{ strtoupper(substr($item->tipe, 0, 2)) }}</div>
                <div>
                    <strong>{{ $item->pesan }}</strong>
                    <p>{{ ucfirst($item->tipe) }} · {{ $item->created_at->diffForHumans() }}</p>
                </div>
                <x-badge :text="$item->is_read ? 'Dibaca' : 'Belum Dibaca'" />
            </div>
        @endforeach
        <div class="pagination-wrap">{{ $notifikasi->links() }}</div>
    @endif
</section>
@endsection
