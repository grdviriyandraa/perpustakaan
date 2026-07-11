<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EksemplarBuku extends Model
{
    protected $table = 'eksemplar_buku';

    protected $fillable = ['buku_id', 'kode_barcode', 'kondisi', 'status'];

    public function buku(): BelongsTo
    {
        return $this->belongsTo(Buku::class, 'buku_id');
    }
}
