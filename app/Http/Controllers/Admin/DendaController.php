<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Denda;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DendaController extends Controller
{
    public function index(Request $request): View
    {
        $denda = Denda::with(['anggota', 'pengembalian.peminjaman.detail.buku'])
            ->when($request->query('status'), fn ($q, $status) => $q->where('status_bayar', $status))
            ->latest('created_at')
            ->paginate(10)
            ->withQueryString();

        $totalBelum = (int) Denda::where('status_bayar', 'belum')->sum('total_denda');
        $totalLunas = (int) Denda::where('status_bayar', 'lunas')->sum('total_denda');

        return view('admin.denda.index', compact('denda', 'totalBelum', 'totalLunas'));
    }

    public function markLunas(Denda $denda): RedirectResponse
    {
        if ($denda->status_bayar === 'lunas') {
            return back()->with('error', 'Denda ini sudah lunas.');
        }

        $denda->update(['status_bayar' => 'lunas']);

        return back()->with('success', "Denda {$denda->kodeDenda()} ditandai lunas.");
    }
}
