<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class NotifikasiController extends Controller
{
    public function index(): View
    {
        $notifikasi = auth('admin')->user()
            ->notifikasi()
            ->latest('created_at')
            ->paginate(15);

        return view('admin.notifikasi.index', compact('notifikasi'));
    }

    public function markAllRead(): RedirectResponse
    {
        auth('admin')->user()
            ->notifikasi()
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return redirect()->route('admin.notifikasi.index')->with('success', 'Semua notifikasi ditandai dibaca.');
    }
}
