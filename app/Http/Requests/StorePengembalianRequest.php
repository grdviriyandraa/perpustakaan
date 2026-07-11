<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePengembalianRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth('admin')->check();
    }

    public function rules(): array
    {
        $peminjaman = $this->route('peminjaman');
        $tanggalPinjam = $peminjaman?->tanggal_pinjam?->toDateString();

        return [
            'tanggal_pengembalian' => [
                'required',
                'date',
                ...($tanggalPinjam ? ['after_or_equal:'.$tanggalPinjam] : []),
            ],
        ];
    }

    public function attributes(): array
    {
        return ['tanggal_pengembalian' => 'tanggal pengembalian'];
    }

    public function messages(): array
    {
        return [
            'tanggal_pengembalian.after_or_equal' => 'Tanggal pengembalian tidak boleh sebelum tanggal pinjam.',
        ];
    }
}
