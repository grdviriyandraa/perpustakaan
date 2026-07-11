<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Anggota;
use App\Services\NotifikasiService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AnggotaController extends Controller
{
    public function __construct(private NotifikasiService $notifikasi)
    {
    }

    public function index(Request $request): View
    {
        $anggota = Anggota::query()
            ->when($request->query('q'), fn ($q, $cari) => $q->where(
                fn ($sub) => $sub->where('nama_lengkap', 'like', "%{$cari}%")
                    ->orWhere('email', 'like', "%{$cari}%")
                    ->orWhere('username', 'like', "%{$cari}%")
            ))
            ->when($request->query('status'), fn ($q, $status) => $q->where('status_akun', $status))
            ->orderByRaw("CASE WHEN status_akun = 'menunggu' THEN 0 ELSE 1 END")
            ->orderBy('nama_lengkap')
            ->paginate(10)
            ->withQueryString();

        return view('admin.anggota.index', compact('anggota'));
    }

    public function show(Anggota $anggota): View
    {
        $anggota->load(['peminjaman.detail.buku', 'denda']);

        return view('admin.anggota.show', compact('anggota'));
    }

    public function updateStatus(Request $request, Anggota $anggota): RedirectResponse
    {
        $request->validate(['status_akun' => ['required', 'in:aktif,ditolak,nonaktif']]);

        $status = $request->input('status_akun');
        $anggota->update(['status_akun' => $status]);

        $pesan = match ($status) {
            'aktif' => 'Selamat! Akun Anda telah diverifikasi dan aktif. Silakan mulai meminjam buku.',
            'ditolak' => 'Mohon maaf, pendaftaran Anda ditolak. Hubungi admin perpustakaan untuk informasi lebih lanjut.',
            'nonaktif' => 'Akun Anda dinonaktifkan. Hubungi admin perpustakaan untuk informasi lebih lanjut.',
        };

        $this->notifikasi->kirimKeAnggota($anggota, 'verifikasi', $pesan);

        $label = match ($status) {
            'aktif' => 'diverifikasi',
            'ditolak' => 'ditolak',
            'nonaktif' => 'dinonaktifkan',
        };

        return back()->with('success', "Anggota {$anggota->nama_lengkap} berhasil {$label}.");
    }
}
