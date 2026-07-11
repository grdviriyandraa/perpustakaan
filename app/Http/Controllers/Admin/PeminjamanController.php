<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Services\PeminjamanService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PeminjamanController extends Controller
{
    public function __construct(private PeminjamanService $peminjamanService)
    {
    }

    public function index(Request $request): View
    {
        $tab = $request->query('tab', 'menunggu');

        $peminjaman = Peminjaman::with(['anggota', 'detail.buku'])
            ->when($tab === 'menunggu', fn ($q) => $q->where('status', 'menunggu'))
            ->when($tab === 'aktif', fn ($q) => $q->whereIn('status', ['disetujui', 'dipinjam']))
            ->when($tab === 'selesai', fn ($q) => $q->whereIn('status', ['selesai', 'ditolak']))
            ->latest('created_at')
            ->paginate(10)
            ->withQueryString();

        return view('admin.peminjaman.index', compact('peminjaman', 'tab'));
    }

    public function show(Peminjaman $peminjaman): View
    {
        $peminjaman->load(['anggota', 'detail.buku', 'pengembalian.denda', 'admin']);

        return view('admin.peminjaman.show', compact('peminjaman'));
    }

    public function validasi(Request $request, Peminjaman $peminjaman): RedirectResponse
    {
        $request->validate([
            'action' => ['required', 'in:setujui,tolak'],
            'catatan' => ['nullable', 'string', 'max:500'],
        ]);

        if ($peminjaman->status !== 'menunggu') {
            return back()->with('error', 'Pengajuan ini sudah divalidasi.');
        }

        $admin = auth('admin')->user();

        if ($request->input('action') === 'setujui') {
            $this->peminjamanService->setujui($peminjaman, $admin);

            return back()->with('success', "Pengajuan {$peminjaman->kodePeminjaman()} disetujui.");
        }

        $this->peminjamanService->tolak($peminjaman, $admin, $request->input('catatan'));

        return back()->with('success', "Pengajuan {$peminjaman->kodePeminjaman()} ditolak.");
    }

    public function konfirmasiAmbil(Peminjaman $peminjaman): RedirectResponse
    {
        if ($peminjaman->status !== 'disetujui') {
            return back()->with('error', 'Hanya pengajuan yang sudah disetujui yang bisa dikonfirmasi.');
        }

        $this->peminjamanService->konfirmasiAmbil($peminjaman);

        return back()->with('success', "Buku {$peminjaman->kodePeminjaman()} dikonfirmasi telah diambil.");
    }
}
