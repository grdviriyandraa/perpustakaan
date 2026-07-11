<?php

namespace App\Http\Controllers\Anggota;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $anggota = auth('anggota')->user();

        $pinjamanAktif = $anggota->peminjaman()->where('status', 'dipinjam')->count();
        $menungguValidasi = $anggota->peminjaman()->whereIn('status', ['menunggu', 'disetujui'])->count();
        $totalDenda = (int) $anggota->dendaBelumBayar()->sum('total_denda');

        $terbaru = $anggota->peminjaman()
            ->with('detail.buku')
            ->latest('created_at')
            ->take(5)
            ->get();

        $rekomendasi = Buku::aktif()
            ->where('stok_tersedia', '>', 0)
            ->latest('created_at')
            ->take(2)
            ->get();

        return view('anggota.dashboard', compact(
            'pinjamanAktif', 'menungguValidasi', 'totalDenda', 'terbaru', 'rekomendasi'
        ));
    }
}
