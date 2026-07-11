<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBukuRequest;
use App\Http\Requests\UpdateBukuRequest;
use App\Models\Buku;
use App\Models\KategoriBuku;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BukuController extends Controller
{
    public function index(Request $request): View
    {
        $buku = Buku::with('kategori')
            ->cari($request->query('q'))
            ->when($request->query('kategori'), fn ($q, $kategori) => $q->where('kategori_id', $kategori))
            ->when($request->query('status'), fn ($q, $status) => $q->where('status_buku', $status))
            ->orderBy('judul')
            ->paginate(10)
            ->withQueryString();

        $kategori = KategoriBuku::orderBy('nama_kategori')->get();

        return view('admin.buku.index', compact('buku', 'kategori'));
    }

    public function create(): View
    {
        $kategori = KategoriBuku::orderBy('nama_kategori')->get();

        return view('admin.buku.create', compact('kategori'));
    }

    public function store(StoreBukuRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['stok_tersedia'] = $data['stok_total'];

        Buku::create($data);

        return redirect()->route('admin.buku.index')->with('success', 'Buku berhasil ditambahkan.');
    }

    public function edit(Buku $buku): View
    {
        $kategori = KategoriBuku::orderBy('nama_kategori')->get();

        return view('admin.buku.edit', compact('buku', 'kategori'));
    }

    public function update(UpdateBukuRequest $request, Buku $buku): RedirectResponse
    {
        $data = $request->validated();

        // Jaga konsistensi stok: selisih stok_total baru diterapkan ke stok_tersedia.
        $selisih = (int) $data['stok_total'] - $buku->stok_total;
        $data['stok_tersedia'] = max(0, min((int) $data['stok_total'], $buku->stok_tersedia + $selisih));

        $buku->update($data);

        return redirect()->route('admin.buku.index')->with('success', 'Data buku berhasil diperbarui.');
    }

    public function destroy(Buku $buku): RedirectResponse
    {
        $sedangDipinjam = $buku->detailPeminjaman()
            ->whereHas('peminjaman', fn ($q) => $q->whereIn('status', ['menunggu', 'disetujui', 'dipinjam']))
            ->exists();

        if ($sedangDipinjam) {
            return redirect()
                ->route('admin.buku.index')
                ->with('error', 'Buku masih dalam transaksi peminjaman aktif, tidak dapat dihapus.');
        }

        // Soft delete: nonaktifkan agar riwayat peminjaman tetap utuh.
        $buku->update(['status_buku' => 'tidak_aktif']);

        return redirect()->route('admin.buku.index')->with('success', 'Buku dinonaktifkan dari katalog.');
    }
}
