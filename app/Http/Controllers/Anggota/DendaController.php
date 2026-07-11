<?php

namespace App\Http\Controllers\Anggota;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class DendaController extends Controller
{
    public function index(): View
    {
        $anggota = auth('anggota')->user();

        $denda = $anggota->denda()
            ->with('pengembalian.peminjaman.detail.buku')
            ->latest('created_at')
            ->get();

        $totalBelum = (int) $anggota->dendaBelumBayar()->sum('total_denda');
        $totalHari = (int) $anggota->dendaBelumBayar()->sum('jumlah_hari_terlambat');

        return view('anggota.denda.index', compact('denda', 'totalBelum', 'totalHari'));
    }
}
