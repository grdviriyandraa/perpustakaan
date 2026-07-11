<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Peminjaman</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #172033;
        }
        h1 {
            font-size: 18px;
            margin: 0 0 4px;
        }
        .sub {
            color: #697386;
            margin: 0 0 18px;
        }
        .stats {
            width: 100%;
            margin-bottom: 18px;
            border-collapse: collapse;
        }
        .stats td {
            width: 33%;
            padding: 10px 12px;
            border: 1px solid #e4edf2;
            background: #f7fbfd;
        }
        .stats strong {
            display: block;
            font-size: 15px;
            margin-top: 3px;
        }
        h2 {
            font-size: 13px;
            margin: 18px 0 8px;
        }
        table.data {
            width: 100%;
            border-collapse: collapse;
        }
        table.data th,
        table.data td {
            padding: 7px 9px;
            border: 1px solid #e4edf2;
            text-align: left;
        }
        table.data th {
            background: #eef4f7;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .footer {
            margin-top: 22px;
            color: #697386;
            font-size: 10px;
        }
    </style>
</head>
<body>
    <h1>Laporan Peminjaman — PerpusApp</h1>
    <p class="sub">Periode {{ $mulai->translatedFormat('d F Y') }} s.d. {{ $sampai->translatedFormat('d F Y') }}</p>

    <table class="stats">
        <tr>
            <td>Total Peminjaman<strong>{{ $totalPeminjaman }}</strong></td>
            <td>Pengembalian Terlambat<strong>{{ $totalTerlambat }}</strong></td>
            <td>Total Denda<strong>{{ rupiah($totalDenda) }}</strong></td>
        </tr>
    </table>

    <h2>Buku Paling Sering Dipinjam</h2>
    <table class="data">
        <thead>
            <tr><th>No</th><th>Judul</th><th>Penulis</th><th>Jumlah Dipinjam</th></tr>
        </thead>
        <tbody>
            @forelse($bukuPopuler as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item->judul }}</td>
                    <td>{{ $item->penulis }}</td>
                    <td>{{ $item->total_pinjam }}</td>
                </tr>
            @empty
                <tr><td colspan="4">Tidak ada data.</td></tr>
            @endforelse
        </tbody>
    </table>

    <h2>Anggota Paling Aktif</h2>
    <table class="data">
        <thead>
            <tr><th>No</th><th>Nama</th><th>Email</th><th>Jumlah Pinjam</th></tr>
        </thead>
        <tbody>
            @forelse($anggotaAktif as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item->nama_lengkap }}</td>
                    <td>{{ $item->email }}</td>
                    <td>{{ $item->total_pinjam }}</td>
                </tr>
            @empty
                <tr><td colspan="4">Tidak ada data.</td></tr>
            @endforelse
        </tbody>
    </table>

    <h2>Peminjaman per Hari</h2>
    <table class="data">
        <thead>
            <tr><th>Tanggal</th><th>Jumlah Peminjaman</th></tr>
        </thead>
        <tbody>
            @forelse($chartData as $hari)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($hari->tanggal)->translatedFormat('d F Y') }}</td>
                    <td>{{ $hari->total }}</td>
                </tr>
            @empty
                <tr><td colspan="2">Tidak ada data.</td></tr>
            @endforelse
        </tbody>
    </table>

    <p class="footer">Dicetak {{ $dicetak->translatedFormat('d F Y H:i') }} oleh {{ auth('admin')->user()->nama_admin }} — PerpusApp.</p>
</body>
</html>
