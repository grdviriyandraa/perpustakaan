@extends('layouts.admin')

@section('title', 'Laporan Peminjaman')
@section('eyebrow', 'Laporan')

@section('content')
<section class="card" style="margin-bottom:18px">
    <form method="GET" action="{{ route('admin.laporan.index') }}">
        <div class="filters" style="grid-template-columns:180px 180px auto auto">
            <input type="date" name="mulai" value="{{ $mulai->toDateString() }}">
            <input type="date" name="sampai" value="{{ $sampai->toDateString() }}">
            <button type="submit" class="btn primary">Terapkan Filter</button>
            <a class="btn secondary" href="{{ route('admin.laporan.pdf', ['mulai' => $mulai->toDateString(), 'sampai' => $sampai->toDateString()]) }}">Ekspor PDF</a>
        </div>
    </form>
</section>

<div class="grid cols-3">
    <x-stat-card label="Total Peminjaman" :value="$totalPeminjaman" icon="TP" />
    <x-stat-card label="Pengembalian Terlambat" :value="$totalTerlambat" icon="TL" />
    <x-stat-card label="Total Denda" :value="rupiah($totalDenda)" icon="DN" />
</div>

<div class="split" style="margin-top:22px">
    <section class="card">
        <div class="card-title"><h3>Grafik Peminjaman per Hari</h3></div>
        @if($chartData->isEmpty())
            <div class="empty-state"><p>Tidak ada data peminjaman pada periode ini.</p></div>
        @else
            @php $maks = max(1, $chartData->max('total')); @endphp
            <div class="chart">
                @foreach($chartData as $hari)
                    <div class="bar" style="height:{{ round(42 + ($hari->total / $maks) * 180) }}px" title="{{ $hari->tanggal }}: {{ $hari->total }} peminjaman"></div>
                @endforeach
            </div>
            <div class="chart-label-row">
                @foreach($chartData as $hari)
                    <span>{{ \Carbon\Carbon::parse($hari->tanggal)->format('d/m') }}</span>
                @endforeach
            </div>
        @endif
    </section>
    <section class="card">
        <h3>Buku Paling Sering Dipinjam</h3>
        <div style="margin-top:16px">
            @if($bukuPopuler->isEmpty())
                <div class="empty-state"><p>Belum ada data.</p></div>
            @else
                <x-table-wrap>
                    <thead>
                        <tr><th>Buku</th><th>Penulis</th><th>Jumlah</th></tr>
                    </thead>
                    <tbody>
                        @foreach($bukuPopuler as $item)
                            <tr>
                                <td>{{ $item->judul }}</td>
                                <td>{{ $item->penulis }}</td>
                                <td>{{ $item->total_pinjam }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </x-table-wrap>
            @endif
        </div>
        <h3 style="margin-top:22px">Anggota Paling Aktif</h3>
        <div style="margin-top:16px">
            @if($anggotaAktif->isEmpty())
                <div class="empty-state"><p>Belum ada data.</p></div>
            @else
                <x-table-wrap>
                    <thead>
                        <tr><th>Anggota</th><th>Jumlah Pinjam</th></tr>
                    </thead>
                    <tbody>
                        @foreach($anggotaAktif as $item)
                            <tr>
                                <td>{{ $item->nama_lengkap }}</td>
                                <td>{{ $item->total_pinjam }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </x-table-wrap>
            @endif
        </div>
    </section>
</div>
@endsection
