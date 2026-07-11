@extends('layouts.admin')

@section('title', 'Dashboard Admin')
@section('eyebrow', 'Dashboard')

@section('content')
<div class="grid cols-4">
    <x-stat-card label="Anggota Aktif" :value="$totalAnggotaAktif" icon="AG" />
    <x-stat-card label="Pinjaman Aktif" :value="$pinjamanAktif" icon="PJ" />
    <x-stat-card label="Terlambat" :value="$terlambat" icon="TL" />
    <x-stat-card label="Denda Belum Dibayar" :value="rupiah($totalDendaBelum)" icon="Rp" />
</div>

<div class="split" style="margin-top:22px">
    <section class="card">
        <div class="card-title">
            <h3>Pengajuan Menunggu Validasi ({{ $menungguValidasi }})</h3>
            <a class="btn primary" href="{{ route('admin.peminjaman.index') }}">Kelola Peminjaman</a>
        </div>
        @if($pengajuanTerbaru->isEmpty())
            <div class="empty-state"><p>Tidak ada pengajuan menunggu.</p></div>
        @else
            <x-table-wrap>
                <thead>
                    <tr><th>Kode</th><th>Anggota</th><th>Buku</th><th>Tanggal</th><th>Aksi</th></tr>
                </thead>
                <tbody>
                    @foreach($pengajuanTerbaru as $item)
                        <tr>
                            <td>{{ $item->kodePeminjaman() }}</td>
                            <td>{{ $item->anggota->nama_lengkap }}</td>
                            <td>{{ $item->detail->first()?->buku?->judul ?? '-' }}</td>
                            <td>{{ $item->tanggal_pengajuan->translatedFormat('d M Y') }}</td>
                            <td><a class="action-link" href="{{ route('admin.peminjaman.show', $item) }}">Detail</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </x-table-wrap>
        @endif
        @if($anggotaMenunggu > 0)
            <p class="notice" style="margin-top:16px">{{ $anggotaMenunggu }} pendaftaran anggota baru menunggu verifikasi. <a class="link-button" href="{{ route('admin.anggota.index', ['status' => 'menunggu']) }}">Lihat daftar</a></p>
        @endif
    </section>
    <section class="card">
        <div class="card-title">
            <h3>Notifikasi Terbaru</h3>
            <a class="btn" href="{{ route('admin.notifikasi.index') }}">Semua</a>
        </div>
        @if($notifikasiTerbaru->isEmpty())
            <div class="empty-state"><p>Belum ada notifikasi.</p></div>
        @else
            @foreach($notifikasiTerbaru as $item)
                <div class="notify-row {{ $item->is_read ? '' : 'unread' }}">
                    <div class="icon-box">{{ strtoupper(substr($item->tipe, 0, 2)) }}</div>
                    <div>
                        <strong>{{ $item->pesan }}</strong>
                        <p>{{ ucfirst($item->tipe) }} · {{ $item->created_at->diffForHumans() }}</p>
                    </div>
                </div>
            @endforeach
        @endif
    </section>
</div>
@endsection
