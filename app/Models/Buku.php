<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Buku extends Model
{
    protected $table = 'buku';

    protected $fillable = [
        'kategori_id', 'kode_buku', 'judul', 'penulis', 'penerbit',
        'tahun_terbit', 'deskripsi', 'lokasi_rak', 'stok_total',
        'stok_tersedia', 'cover_color', 'status_buku',
    ];

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(KategoriBuku::class, 'kategori_id');
    }

    public function eksemplar(): HasMany
    {
        return $this->hasMany(EksemplarBuku::class, 'buku_id');
    }

    public function detailPeminjaman(): HasMany
    {
        return $this->hasMany(DetailPeminjaman::class, 'buku_id');
    }

    public function wishlist(): HasMany
    {
        return $this->hasMany(Wishlist::class, 'buku_id');
    }

    public function scopeAktif(Builder $query): Builder
    {
        return $query->where('status_buku', '!=', 'tidak_aktif');
    }

    public function scopeCari(Builder $query, ?string $keyword): Builder
    {
        return $query->when($keyword, fn (Builder $q) => $q->where(
            fn (Builder $sub) => $sub
                ->where('judul', 'like', "%{$keyword}%")
                ->orWhere('penulis', 'like', "%{$keyword}%")
                ->orWhere('kode_buku', 'like', "%{$keyword}%")
        ));
    }

    public function statusLabel(): string
    {
        if ($this->status_buku === 'tidak_aktif') {
            return 'Nonaktif';
        }

        return $this->stok_tersedia > 0 ? 'Tersedia' : 'Stok Habis';
    }
}
