<?php

use App\Models\SystemSetting;

if (! function_exists('setting')) {
    /**
     * Ambil nilai pengaturan sistem berdasarkan key.
     */
    function setting(string $key, mixed $default = null): mixed
    {
        return SystemSetting::query()->where('key', $key)->value('value') ?? $default;
    }
}

if (! function_exists('rupiah')) {
    /**
     * Format angka menjadi format Rupiah.
     */
    function rupiah(int|float $angka): string
    {
        return 'Rp'.number_format($angka, 0, ',', '.');
    }
}
