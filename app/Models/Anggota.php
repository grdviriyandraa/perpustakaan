<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Anggota extends Authenticatable
{
    protected $guard = 'anggota';

    protected $table = 'anggota';

    protected $fillable = [
        'nama_lengkap', 'username', 'email', 'password',
        'no_telp', 'alamat', 'status_akun', 'tanggal_daftar',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'tanggal_daftar' => 'date',
        ];
    }

    public function peminjaman(): HasMany
    {
        return $this->hasMany(Peminjaman::class, 'anggota_id');
    }

    public function denda(): HasMany
    {
        return $this->hasMany(Denda::class, 'anggota_id');
    }

    public function wishlist(): HasMany
    {
        return $this->hasMany(Wishlist::class, 'anggota_id');
    }

    public function notifikasi(): HasMany
    {
        return $this->hasMany(Notifikasi::class, 'anggota_id');
    }

    public function peminjamanAktif(): HasMany
    {
        return $this->peminjaman()->whereIn('status', ['menunggu', 'disetujui', 'dipinjam']);
    }

    public function dendaBelumBayar(): HasMany
    {
        return $this->denda()->where('status_bayar', 'belum');
    }

    public function kodeAnggota(): string
    {
        return 'A-'.str_pad((string) $this->id, 3, '0', STR_PAD_LEFT);
    }
}
