<?php

namespace App\Http\Controllers\Anggota;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class NotifikasiController extends Controller
{
    public function index(): View
    {
        $notifikasi = auth('anggota')->user()
            ->notifikasi()
            ->latest('created_at')
            ->paginate(15);

        return view('anggota.notifikasi.index', compact('notifikasi'));
    }

    public function markAllRead(): RedirectResponse
    {
        auth('anggota')->user()
            ->notifikasi()
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return redirect()->route('anggota.notifikasi')->with('success', 'Semua notifikasi ditandai dibaca.');
    }
}
