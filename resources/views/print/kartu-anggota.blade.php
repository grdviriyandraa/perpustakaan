<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kartu Anggota — {{ $anggota->kodeAnggota() }}</title>
    <style>
        @page { margin: 0; }
        body {
            margin: 0;
            font-family: DejaVu Sans, sans-serif;
            color: #ffffff;
        }
        .kartu {
            width: 340px;
            height: 215px;
            padding: 24px 26px;
            box-sizing: border-box;
            background: #172033;
            background-image: linear-gradient(135deg, #172033, #236577 68%, #36bfd0);
            position: relative;
            overflow: hidden;
        }
        .brand {
            font-size: 13px;
            font-weight: bold;
            letter-spacing: 0.04em;
        }
        .brand .mark {
            display: inline-block;
            width: 26px;
            height: 26px;
            line-height: 26px;
            text-align: center;
            border-radius: 8px;
            background: #e9f8fb;
            color: #159bad;
            font-weight: bold;
            margin-right: 8px;
        }
        h2 {
            margin: 34px 0 8px;
            font-size: 21px;
        }
        p {
            margin: 3px 0;
            font-size: 11px;
            color: #dcebf2;
        }
        .lingkaran {
            position: absolute;
            width: 180px;
            height: 180px;
            right: -70px;
            bottom: -80px;
            border-radius: 999px;
            background: rgba(255, 201, 40, 0.34);
        }
    </style>
</head>
<body>
    <div class="kartu">
        <div class="lingkaran"></div>
        <div class="brand"><span class="mark">PA</span>PerpusApp</div>
        <h2>{{ $anggota->nama_lengkap }}</h2>
        <p>ID Anggota: {{ $anggota->kodeAnggota() }}</p>
        <p>Status: Terverifikasi</p>
        <p>Terdaftar: {{ $anggota->tanggal_daftar->translatedFormat('d F Y') }}</p>
    </div>
</body>
</html>
