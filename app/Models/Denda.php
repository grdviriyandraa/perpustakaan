<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Denda extends Model
{
    protected $table = 'denda';

    protected $fillable = [
        'pengembalian_id', 'anggota_id', 'jumlah_hari_terlambat',
        'tarif_per_hari', 'total_denda', 'status_bayar',
    ];

    public function pengembalian(): BelongsTo
    {
        return $this->belongsTo(Pengembalian::class, 'pengembalian_id');
    }

    public function anggota(): BelongsTo
    {
        return $this->belongsTo(Anggota::class, 'anggota_id');
    }

    public function kodeDenda(): string
    {
        return 'DND-'.str_pad((string) $this->id, 3, '0', STR_PAD_LEFT);
    }
}
