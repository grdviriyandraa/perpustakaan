<div class="grid cols-2">
    <section class="card">
        <h3>Panduan Anggota</h3>
        <div class="notify-row">
            <div class="icon-box">1</div>
            <div><strong>Daftar dan tunggu verifikasi</strong><p>Isi formulir registrasi. Akun aktif setelah disetujui admin.</p></div>
        </div>
        <div class="notify-row">
            <div class="icon-box">2</div>
            <div><strong>Cari buku</strong><p>Gunakan katalog untuk menemukan judul dan cek ketersediaan stok.</p></div>
        </div>
        <div class="notify-row">
            <div class="icon-box">3</div>
            <div><strong>Ajukan peminjaman</strong><p>Pengajuan divalidasi admin. Setelah disetujui, ambil buku langsung di perpustakaan.</p></div>
        </div>
        <div class="notify-row">
            <div class="icon-box">4</div>
            <div><strong>Kembalikan tepat waktu</strong><p>Keterlambatan dikenakan denda per hari sesuai pengaturan perpustakaan.</p></div>
        </div>
    </section>
    <section class="card">
        <h3>Pertanyaan Umum</h3>
        <div class="notify-row">
            <div class="icon-box">?</div>
            <div><strong>Berapa lama durasi peminjaman?</strong><p>{{ setting('durasi_peminjaman', 7) }} hari sejak pengajuan disetujui.</p></div>
        </div>
        <div class="notify-row">
            <div class="icon-box">?</div>
            <div><strong>Berapa banyak buku yang bisa dipinjam?</strong><p>Maksimal {{ setting('batas_peminjaman', 3) }} buku aktif per anggota.</p></div>
        </div>
        <div class="notify-row">
            <div class="icon-box">?</div>
            <div><strong>Berapa tarif dendanya?</strong><p>{{ rupiah((int) setting('tarif_denda_harian', 5000)) }} per hari keterlambatan, dibayar di perpustakaan.</p></div>
        </div>
        <div class="notify-row">
            <div class="icon-box">?</div>
            <div><strong>Kenapa tidak bisa mengajukan pinjaman?</strong><p>Pastikan akun aktif, tidak melebihi batas pinjaman, dan tidak ada denda tertunggak.</p></div>
        </div>
    </section>
</div>
