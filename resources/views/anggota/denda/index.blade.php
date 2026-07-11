@extends('layouts.anggota')

@section('title', 'Informasi Denda')
@section('eyebrow', 'Denda')

@section('content')
<div class="grid cols-3">
    <x-stat-card label="Total Denda Belum Dibayar" :value="rupiah($totalBelum)" icon="Rp" />
    <x-stat-card label="Hari Terlambat" :value="$totalHari" icon="HT" />
    <x-stat-card label="Status Pembayaran" :value="$totalBelum > 0 ? 'Belum Lunas' : 'Bersih'" icon="DN" />
</div>

<section class="card" style="margin-top:22px">
    <div class="card-title"><h3>Daftar Denda</h3></div>
    @if($denda->isEmpty())
        <div class="empty-state">
            <h3>Tidak ada denda</h3>
            <p>Kembalikan buku tepat waktu agar tetap bebas denda.</p>
        </div>
    @else
        <x-table-wrap>
            <thead>
                <tr><th>Kode</th><th>Buku</th><th>Hari Terlambat</th><th>Tarif</th><th>Total</th><th>Status</th></tr>
            </thead>
            <tbody>
                @foreach($denda as $item)
                    <tr>
                        <td>{{ $item->kodeDenda() }}</td>
                        <td>{{ $item->pengembalian?->peminjaman?->detail->first()?->buku?->judul ?? '-' }}</td>
                        <td>{{ $item->jumlah_hari_terlambat }} hari</td>
                        <td>{{ rupiah($item->tarif_per_hari) }}</td>
                        <td>{{ rupiah($item->total_denda) }}</td>
                        <td><x-badge :text="$item->status_bayar === 'lunas' ? 'Lunas' : 'Belum Bayar'" /></td>
                    </tr>
                @endforeach
            </tbody>
        </x-table-wrap>
        <p class="notice">Pembayaran denda dilakukan langsung di perpustakaan. Denda yang belum lunas memblokir pengajuan peminjaman baru.</p>
    @endif
</section>
@endsection
