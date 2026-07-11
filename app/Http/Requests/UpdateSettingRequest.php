<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSettingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth('admin')->check();
    }

    public function rules(): array
    {
        return [
            'tarif_denda_harian' => ['required', 'integer', 'min:0'],
            'durasi_peminjaman' => ['required', 'integer', 'min:1', 'max:90'],
            'batas_peminjaman' => ['required', 'integer', 'min:1', 'max:20'],
            'verifikasi_akun' => ['required', 'in:aktif,nonaktif'],
        ];
    }

    public function attributes(): array
    {
        return [
            'tarif_denda_harian' => 'tarif denda harian',
            'durasi_peminjaman' => 'durasi peminjaman',
            'batas_peminjaman' => 'batas peminjaman',
            'verifikasi_akun' => 'verifikasi akun',
        ];
    }
}
