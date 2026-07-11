<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    protected $guard = 'admin';

    protected $table = 'admins';

    protected $fillable = ['nama_admin', 'username', 'email', 'password'];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return ['password' => 'hashed'];
    }

    public function peminjaman(): HasMany
    {
        return $this->hasMany(Peminjaman::class, 'admin_id');
    }

    public function pengembalian(): HasMany
    {
        return $this->hasMany(Pengembalian::class, 'admin_id');
    }

    public function notifikasi(): HasMany
    {
        return $this->hasMany(Notifikasi::class, 'admin_id');
    }
}
