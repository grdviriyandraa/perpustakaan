<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Pengembalian extends Model
{
    protected $table = 'pengembalian';

    protected $fillable = [
        'peminjaman_id', 'admin_id', 'tanggal_pengembalian',
        'status_pengembalian', 'total_hari_terlambat',
    ];

    protected function casts(): array
    {
        return ['tanggal_pengembalian' => 'date'];
    }

    public function peminjaman(): BelongsTo
    {
        return $this->belongsTo(Peminjaman::class, 'peminjaman_id');
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }

    public function denda(): HasOne
    {
        return $this->hasOne(Denda::class, 'pengembalian_id');
    }
}
