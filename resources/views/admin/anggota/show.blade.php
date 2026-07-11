@extends('layouts.admin')

@section('title', 'Detail Anggota')
@section('eyebrow', 'Anggota')

@section('content')
<div class="split">
    <section class="card">
        <div class="card-title">
            <h3>{{ $anggota->nama_lengkap }}</h3>
            <a class="btn" href="{{ route('admin.anggota.index') }}">Kembali</a>
        </div>
        <x-badge :text="$anggota->status_akun === 'aktif' ? 'Terverifikasi' : ucfirst($anggota->status_akun)" />
        <div class="info-grid">
            <div class="info-item"><p>ID Anggota</p><strong>{{ $anggota->kodeAnggota() }}</strong></div>
            <div class="info-item"><p>Username</p><strong>{{ $anggota->username }}</strong></div>
            <div class="info-item"><p>Email</p><strong>{{ $anggota->email }}</strong></div>
            <div class="info-item"><p>Nomor Telepon</p><strong>{{ $anggota->no_telp ?? '-' }}</strong></div>
            <div class="info-item"><p>Tanggal Daftar</p><strong>{{ $anggota->tanggal_daftar->translatedFormat('d F Y') }}</strong></div>
            <div class="info-item"><p>Alamat</p><strong>{{ $anggota->alamat ?? '-' }}</strong></div>
        </div>
        <div style="display:flex;gap:12px;flex-wrap:wrap">
            @if($anggota->status_akun === 'menunggu')
                <form method="POST" action="{{ route('admin.anggota.status', $anggota) }}">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status_akun" value="aktif">
                    <button type="submit" class="btn ok">Verifikasi</button>
                </form>
                <form method="POST" action="{{ route('admin.anggota.status', $anggota) }}">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status_akun" value="ditolak">
                    <button type="submit" class="btn warn">Tolak</button>
                </form>
            @elseif($anggota->status_akun === 'aktif')
                <form method="POST" action="{{ route('admin.anggota.status', $anggota) }}" onsubmit="return confirm('Nonaktifkan anggota ini?')">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status_akun" value="nonaktif">
                    <button type="submit" class="btn warn">Nonaktifkan</button>
                </form>
                <a class="btn secondary" href="{{ route('admin.kartu.index', ['anggota' => $anggota->id]) }}">Cetak Kartu</a>
            @else
                <form method="POST" action="{{ route('admin.anggota.status', $anggota) }}">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status_akun" value="aktif">
                    <button type="submit" class="btn ok">Aktifkan</button>
                </form>
            @endif
        </div>
    </section>
    <section class="card">
        <h3>Riwayat Peminjaman</h3>
        <div style="margin-top:16px">
            @if($anggota->peminjaman->isEmpty())
                <div class="empty-state"><p>Belum ada transaksi.</p></div>
            @else
                <x-table-wrap>
                    <thead>
                        <tr><th>Kode</th><th>Buku</th><th>Status</th></tr>
                    </thead>
                    <tbody>
                        @foreach($anggota->peminjaman->sortByDesc('created_at')->take(8) as $item)
                            <tr>
                                <td>{{ $item->kodePeminjaman() }}</td>
                                <td>{{ $item->detail->first()?->buku?->judul ?? '-' }}</td>
                                <td><x-badge :text="$item->statusLabel()" /></td>
                            </tr>
                        @endforeach
                    </tbody>
                </x-table-wrap>
            @endif
        </div>
        <h3 style="margin-top:22px">Denda</h3>
        <p style="margin-top:8px">
            Total belum dibayar:
            <strong>{{ rupiah((int) $anggota->denda->where('status_bayar', 'belum')->sum('total_denda')) }}</strong>
        </p>
    </section>
</div>
@endsection
