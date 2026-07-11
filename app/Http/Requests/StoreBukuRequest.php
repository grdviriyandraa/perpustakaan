<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBukuRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth('admin')->check();
    }

    public function rules(): array
    {
        return [
            'kode_buku' => ['required', 'string', 'max:30', 'unique:buku,kode_buku'],
            'judul' => ['required', 'string', 'max:255'],
            'penulis' => ['required', 'string', 'max:150'],
            'penerbit' => ['nullable', 'string', 'max:150'],
            'tahun_terbit' => ['nullable', 'integer', 'digits:4', 'min:1900', 'max:'.date('Y')],
            'kategori_id' => ['required', 'exists:kategori_buku,id'],
            'deskripsi' => ['nullable', 'string'],
            'stok_total' => ['required', 'integer', 'min:1'],
            'lokasi_rak' => ['nullable', 'string', 'max:30'],
            'cover_color' => ['required', 'in:cover-a,cover-b,cover-c,cover-d'],
        ];
    }

    public function attributes(): array
    {
        return [
            'kode_buku' => 'kode buku',
            'tahun_terbit' => 'tahun terbit',
            'kategori_id' => 'kategori',
            'stok_total' => 'stok total',
            'lokasi_rak' => 'lokasi rak',
            'cover_color' => 'warna sampul',
        ];
    }
}
