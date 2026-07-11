<?php

namespace App\Http\Controllers\Anggota;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Services\PeminjamanService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PeminjamanController extends Controller
{
    public function __construct(private PeminjamanService $peminjamanService)
    {
    }

    public function showForm(Buku $buku): View
    {
        abort_if($buku->status_buku === 'tidak_aktif', 404);

        $anggota = auth('anggota')->user();
        $cek = $this->peminjamanService->bisaPinjam($anggota, $buku);

        return view('anggota.peminjaman.ajukan', compact('buku', 'cek'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate(['buku_id' => ['required', 'exists:buku,id']]);

        $buku = Buku::findOrFail($request->input('buku_id'));
        $anggota = auth('anggota')->user();

        $cek = $this->peminjamanService->bisaPinjam($anggota, $buku);

        if (! $cek['bisa']) {
            return redirect()
                ->route('anggota.katalog.detail', $buku)
                ->with('error', $cek['alasan']);
        }

        try {
            $this->peminjamanService->ajukan($anggota, $buku);
        } catch (\RuntimeException $e) {
            return redirect()
                ->route('anggota.katalog.detail', $buku)
                ->with('error', $e->getMessage());
        }

        return redirect()
            ->route('anggota.peminjaman.status')
            ->with('success', 'Pengajuan peminjaman terkirim. Menunggu validasi admin.');
    }

    public function status(): View
    {
        $peminjaman = auth('anggota')->user()
            ->peminjaman()
            ->with('detail.buku')
            ->whereIn('status', ['menunggu', 'disetujui', 'dipinjam'])
            ->latest('created_at')
            ->get();

        return view('anggota.peminjaman.status', compact('peminjaman'));
    }

    public function riwayat(): View
    {
        $peminjaman = auth('anggota')->user()
            ->peminjaman()
            ->with(['detail.buku', 'pengembalian'])
            ->latest('created_at')
            ->paginate(10);

        return view('anggota.peminjaman.riwayat', compact('peminjaman'));
    }
}
