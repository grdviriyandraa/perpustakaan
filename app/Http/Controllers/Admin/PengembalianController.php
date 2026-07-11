<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePengembalianRequest;
use App\Models\Denda;
use App\Models\Peminjaman;
use App\Services\DendaService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PengembalianController extends Controller
{
    public function __construct(private DendaService $dendaService)
    {
    }

    public function index(): View
    {
        $peminjaman = Peminjaman::with(['anggota', 'detail.buku'])
            ->where('status', 'dipinjam')
            ->orderBy('tanggal_jatuh_tempo')
            ->paginate(10);

        $totalAktif = Peminjaman::where('status', 'dipinjam')->count();

        $totalTerlambat = Peminjaman::where('status', 'dipinjam')
            ->whereDate('tanggal_jatuh_tempo', '<', now()->toDateString())
            ->count();

        $dendaHariIni = (int) Denda::whereDate('created_at', now()->toDateString())->sum('total_denda');

        return view('admin.pengembalian.index', compact('peminjaman', 'totalAktif', 'totalTerlambat', 'dendaHariIni'));
    }

    public function form(Peminjaman $peminjaman): View|RedirectResponse
    {
        if ($peminjaman->status !== 'dipinjam') {
            return redirect()
                ->route('admin.pengembalian.index')
                ->with('error', 'Peminjaman ini tidak berstatus dipinjam.');
        }

        $peminjaman->load(['anggota', 'detail.buku']);
        $preview = $this->dendaService->hitung($peminjaman, now());

        return view('admin.pengembalian.catat', compact('peminjaman', 'preview'));
    }

    public function store(StorePengembalianRequest $request, Peminjaman $peminjaman): RedirectResponse
    {
        if ($peminjaman->status !== 'dipinjam') {
            return redirect()
                ->route('admin.pengembalian.index')
                ->with('error', 'Peminjaman ini tidak berstatus dipinjam.');
        }

        $tanggalKembali = Carbon::parse($request->input('tanggal_pengembalian'));
        $pengembalian = $this->dendaService->catatPengembalian($peminjaman, $tanggalKembali, auth('admin')->user());

        $pesan = $pengembalian->total_hari_terlambat > 0
            ? "Pengembalian dicatat. Terlambat {$pengembalian->total_hari_terlambat} hari, denda ".rupiah($pengembalian->denda->total_denda).'.'
            : 'Pengembalian dicatat. Tepat waktu, tanpa denda.';

        return redirect()->route('admin.pengembalian.index')->with('success', $pesan);
    }
}
