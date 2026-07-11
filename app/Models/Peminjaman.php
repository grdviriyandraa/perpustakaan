<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Peminjaman extends Model
{
    protected $table = 'peminjaman';

    protected $fillable = [
        'anggota_id', 'admin_id', 'tanggal_pengajuan', 'tanggal_pinjam',
        'tanggal_jatuh_tempo', 'status', 'catatan',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_pengajuan' => 'date',
            'tanggal_pinjam' => 'date',
            'tanggal_jatuh_tempo' => 'date',
        ];
    }

    public function anggota(): BelongsTo
    {
        return $this->belongsTo(Anggota::class, 'anggota_id');
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }

    public function detail(): HasMany
    {
        return $this->hasMany(DetailPeminjaman::class, 'peminjaman_id');
    }

    public function pengembalian(): HasOne
    {
        return $this->hasOne(Pengembalian::class, 'peminjaman_id');
    }

    public function kodePeminjaman(): string
    {
        return 'PMJ-'.str_pad((string) $this->id, 3, '0', STR_PAD_LEFT);
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            'menunggu' => 'Menunggu',
            'disetujui' => 'Disetujui',
            'ditolak' => 'Ditolak',
            'dipinjam' => 'Dipinjam',
            'selesai' => 'Selesai',
            default => ucfirst($this->status),
        };
    }

    public function isTerlambat(): bool
    {
        return $this->status === 'dipinjam'
            && $this->tanggal_jatuh_tempo !== null
            && now()->startOfDay()->gt($this->tanggal_jatuh_tempo);
    }

    public function hariTerlambat(): int
    {
        if (! $this->isTerlambat()) {
            return 0;
        }

        return (int) $this->tanggal_jatuh_tempo->startOfDay()->diffInDays(now()->startOfDay());
    }
}
