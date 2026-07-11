<article class="card book-card">
    <div class="book-cover {{ $buku->cover_color }}">
        <span>{{ $buku->judul }}</span>
        <small>{{ $buku->kategori?->nama_kategori ?? 'Umum' }}</small>
    </div>
    <div class="book-meta">
        <strong>{{ $buku->judul }}</strong>
        <p>{{ $buku->penulis }}</p>
    </div>
    <div class="book-actions">
        <x-badge :text="$buku->statusLabel()" />
        <a class="btn" href="{{ route('anggota.katalog.detail', $buku) }}">Detail</a>
    </div>
</article>
