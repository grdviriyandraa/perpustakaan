<?php

namespace App\Http\Controllers\Anggota;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\KategoriBuku;
use App\Services\PeminjamanService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BukuController extends Controller
{
    public function __construct(private PeminjamanService $peminjamanService)
    {
    }

    public function index(Request $request): View
    {
        $buku = Buku::aktif()
            ->with('kategori')
            ->cari($request->query('q'))
            ->when($request->query('kategori'), fn ($q, $kategori) => $q->where('kategori_id', $kategori))
            ->when($request->query('status') === 'tersedia', fn ($q) => $q->where('stok_tersedia', '>', 0))
            ->when($request->query('status') === 'habis', fn ($q) => $q->where('stok_tersedia', 0))
            ->orderBy('judul')
            ->paginate(8)
            ->withQueryString();

        $kategori = KategoriBuku::orderBy('nama_kategori')->get();

        return view('anggota.katalog.index', compact('buku', 'kategori'));
    }

    public function show(Buku $buku): View
    {
        abort_if($buku->status_buku === 'tidak_aktif', 404);

        $buku->load('kategori');

        $serupa = Buku::aktif()
            ->where('id', '!=', $buku->id)
            ->where('kategori_id', $buku->kategori_id)
            ->take(2)
            ->get();

        $anggota = auth('anggota')->user();
        $cek = $this->peminjamanService->bisaPinjam($anggota, $buku);
        $diWishlist = $anggota->wishlist()->where('buku_id', $buku->id)->exists();

        return view('anggota.katalog.detail', compact('buku', 'serupa', 'cek', 'diWishlist'));
    }
}
