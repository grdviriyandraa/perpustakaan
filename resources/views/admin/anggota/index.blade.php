@extends('layouts.admin')

@section('title', 'Kelola Anggota')
@section('eyebrow', 'Anggota')

@section('content')
<section class="card">
    <div class="card-title">
        <h3>Data Anggota</h3>
    </div>
    <form method="GET" action="{{ route('admin.anggota.index') }}">
        <div class="filters" style="grid-template-columns:minmax(220px,1fr) 180px;margin-bottom:16px">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama, email, atau username">
            <select name="status" onchange="this.form.submit()">
                <option value="">Semua Status</option>
                @foreach(['menunggu' => 'Menunggu', 'aktif' => 'Aktif', 'ditolak' => 'Ditolak', 'nonaktif' => 'Nonaktif'] as $value => $label)
                    <option value="{{ $value }}" @selected(request('status') === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
    </form>
    @if($anggota->isEmpty())
        <div class="empty-state"><h3>Tidak ada anggota</h3><p>Belum ada anggota yang cocok dengan filter.</p></div>
    @else
        <x-table-wrap>
            <thead>
                <tr><th>ID</th><th>Nama</th><th>Email</th><th>Tanggal Daftar</th><th>Status</th><th>Aksi</th></tr>
            </thead>
            <tbody>
                @foreach($anggota as $item)
                    <tr>
                        <td>{{ $item->kodeAnggota() }}</td>
                        <td>{{ $item->nama_lengkap }}</td>
                        <td>{{ $item->email }}</td>
                        <td>{{ $item->tanggal_daftar->translatedFormat('d M Y') }}</td>
                        <td><x-badge :text="$item->status_akun === 'aktif' ? 'Terverifikasi' : ucfirst($item->status_akun)" /></td>
                        <td>
                            <a class="action-link" href="{{ route('admin.anggota.show', $item) }}">Detail</a>
                            @if($item->status_akun === 'menunggu')
                                |
                                <form class="inline-form" method="POST" action="{{ route('admin.anggota.status', $item) }}">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status_akun" value="aktif">
                                    <button type="submit" class="action-link" style="color:var(--green)">Verifikasi</button>
                                </form>
                                |
                                <form class="inline-form" method="POST" action="{{ route('admin.anggota.status', $item) }}">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status_akun" value="ditolak">
                                    <button type="submit" class="action-link" style="color:var(--red)">Tolak</button>
                                </form>
                            @elseif($item->status_akun === 'aktif')
                                |
                                <form class="inline-form" method="POST" action="{{ route('admin.anggota.status', $item) }}" onsubmit="return confirm('Nonaktifkan anggota ini?')">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status_akun" value="nonaktif">
                                    <button type="submit" class="action-link" style="color:var(--red)">Nonaktifkan</button>
                                </form>
                            @else
                                |
                                <form class="inline-form" method="POST" action="{{ route('admin.anggota.status', $item) }}">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status_akun" value="aktif">
                                    <button type="submit" class="action-link" style="color:var(--green)">Aktifkan</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </x-table-wrap>
        <div class="pagination-wrap">{{ $anggota->links() }}</div>
    @endif
</section>
@endsection
