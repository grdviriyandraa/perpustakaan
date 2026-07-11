@extends('layouts.admin')

@section('title', 'Informasi Denda')
@section('eyebrow', 'Denda')

@section('content')
<div class="grid cols-2">
    <x-stat-card label="Total Belum Dibayar" :value="rupiah($totalBelum)" icon="Rp" />
    <x-stat-card label="Total Sudah Lunas" :value="rupiah($totalLunas)" icon="OK" />
</div>

<section class="card" style="margin-top:22px">
    <div class="card-title">
        <h3>Daftar Denda</h3>
        <form method="GET" action="{{ route('admin.denda.index') }}">
            <select name="status" onchange="this.form.submit()" style="width:180px">
                <option value="">Semua Status</option>
                <option value="belum" @selected(request('status') === 'belum')>Belum Bayar</option>
                <option value="lunas" @selected(request('status') === 'lunas')>Lunas</option>
            </select>
        </form>
    </div>
    @if($denda->isEmpty())
        <div class="empty-state">
            <h3>Tidak ada denda</h3>
            <p>Belum ada denda yang tercatat.</p>
        </div>
    @else
        <x-table-wrap>
            <thead>
                <tr><th>Kode</th><th>Anggota</th><th>Buku</th><th>Hari Terlambat</th><th>Tarif</th><th>Total</th><th>Status</th><th>Aksi</th></tr>
            </thead>
            <tbody>
                @foreach($denda as $item)
                    <tr>
                        <td>{{ $item->kodeDenda() }}</td>
                        <td>{{ $item->anggota->nama_lengkap }}</td>
                        <td>{{ $item->pengembalian?->peminjaman?->detail->first()?->buku?->judul ?? '-' }}</td>
                        <td>{{ $item->jumlah_hari_terlambat }}</td>
                        <td>{{ rupiah($item->tarif_per_hari) }}</td>
                        <td>{{ rupiah($item->total_denda) }}</td>
                        <td><x-badge :text="$item->status_bayar === 'lunas' ? 'Lunas' : 'Belum Bayar'" /></td>
                        <td>
                            @if($item->status_bayar === 'belum')
                                <form class="inline-form" method="POST" action="{{ route('admin.denda.lunas', $item) }}" onsubmit="return confirm('Tandai denda ini lunas?')">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="action-link">Tandai Lunas</button>
                                </form>
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </x-table-wrap>
        <div class="pagination-wrap">{{ $denda->links() }}</div>
    @endif
</section>
@endsection
