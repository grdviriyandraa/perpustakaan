{{-- Partial form buku: dipakai create & edit. Variabel: $buku (nullable), $kategori --}}
<div class="form-grid">
    <div class="field">
        <label for="kode_buku">Kode Buku</label>
        <input id="kode_buku" type="text" name="kode_buku" value="{{ old('kode_buku', $buku->kode_buku ?? '') }}" required>
        <x-field-error name="kode_buku" />
    </div>
    <div class="field">
        <label for="judul">Judul</label>
        <input id="judul" type="text" name="judul" value="{{ old('judul', $buku->judul ?? '') }}" required>
        <x-field-error name="judul" />
    </div>
    <div class="field">
        <label for="penulis">Penulis</label>
        <input id="penulis" type="text" name="penulis" value="{{ old('penulis', $buku->penulis ?? '') }}" required>
        <x-field-error name="penulis" />
    </div>
    <div class="field">
        <label for="penerbit">Penerbit</label>
        <input id="penerbit" type="text" name="penerbit" value="{{ old('penerbit', $buku->penerbit ?? '') }}">
        <x-field-error name="penerbit" />
    </div>
    <div class="field">
        <label for="tahun_terbit">Tahun Terbit</label>
        <input id="tahun_terbit" type="number" name="tahun_terbit" min="1900" max="{{ date('Y') }}" value="{{ old('tahun_terbit', $buku->tahun_terbit ?? '') }}">
        <x-field-error name="tahun_terbit" />
    </div>
    <div class="field">
        <label for="kategori_id">Kategori</label>
        <select id="kategori_id" name="kategori_id" required>
            <option value="">Pilih kategori</option>
            @foreach($kategori as $item)
                <option value="{{ $item->id }}" @selected(old('kategori_id', $buku->kategori_id ?? '') == $item->id)>{{ $item->nama_kategori }}</option>
            @endforeach
        </select>
        <x-field-error name="kategori_id" />
    </div>
    <div class="field">
        <label for="stok_total">Stok Total</label>
        <input id="stok_total" type="number" name="stok_total" min="1" value="{{ old('stok_total', $buku->stok_total ?? 1) }}" required>
        <x-field-error name="stok_total" />
    </div>
    <div class="field">
        <label for="lokasi_rak">Lokasi Rak</label>
        <input id="lokasi_rak" type="text" name="lokasi_rak" value="{{ old('lokasi_rak', $buku->lokasi_rak ?? '') }}" placeholder="R-01">
        <x-field-error name="lokasi_rak" />
    </div>
    <div class="field">
        <label for="cover_color">Warna Sampul</label>
        <select id="cover_color" name="cover_color" required>
            @foreach(['cover-a' => 'Biru Tosca', 'cover-b' => 'Merah Kuning', 'cover-c' => 'Navy Ungu', 'cover-d' => 'Hijau'] as $value => $label)
                <option value="{{ $value }}" @selected(old('cover_color', $buku->cover_color ?? 'cover-a') === $value)>{{ $label }}</option>
            @endforeach
        </select>
        <x-field-error name="cover_color" />
    </div>
    @isset($buku)
        <div class="field">
            <label for="status_buku">Status Buku</label>
            <select id="status_buku" name="status_buku" required>
                @foreach(['tersedia' => 'Tersedia', 'dipinjam' => 'Dipinjam', 'tidak_aktif' => 'Tidak Aktif'] as $value => $label)
                    <option value="{{ $value }}" @selected(old('status_buku', $buku->status_buku) === $value)>{{ $label }}</option>
                @endforeach
            </select>
            <x-field-error name="status_buku" />
        </div>
    @endisset
</div>
<div class="field">
    <label for="deskripsi">Deskripsi</label>
    <textarea id="deskripsi" name="deskripsi">{{ old('deskripsi', $buku->deskripsi ?? '') }}</textarea>
    <x-field-error name="deskripsi" />
</div>
