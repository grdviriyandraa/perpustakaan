<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Anggota;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class KartuAnggotaController extends Controller
{
    public function index(Request $request): View
    {
        $hasil = Anggota::where('status_akun', 'aktif')
            ->when($request->query('q'), fn ($q, $cari) => $q->where(
                fn ($sub) => $sub->where('nama_lengkap', 'like', "%{$cari}%")
                    ->orWhere('email', 'like', "%{$cari}%")
                    ->orWhere('username', 'like', "%{$cari}%")
            ))
            ->orderBy('nama_lengkap')
            ->paginate(8)
            ->withQueryString();

        $pilih = $request->query('anggota')
            ? Anggota::where('status_akun', 'aktif')->find($request->query('anggota'))
            : null;

        return view('admin.kartu.index', compact('hasil', 'pilih'));
    }

    public function cetak(Anggota $anggota): Response
    {
        abort_if($anggota->status_akun !== 'aktif', 404);

        $pdf = Pdf::loadView('print.kartu-anggota', compact('anggota'))
            ->setPaper([0, 0, 340, 215]);

        return $pdf->download('kartu-anggota-'.$anggota->kodeAnggota().'.pdf');
    }
}
