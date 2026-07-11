<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Anggota;
use App\Models\Denda;
use App\Models\Peminjaman;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $totalAnggotaAktif = Anggota::where('status_akun', 'aktif')->count();
        $anggotaMenunggu = Anggota::where('status_akun', 'menunggu')->count();
        $pinjamanAktif = Peminjaman::where('status', 'dipinjam')->count();
        $menungguValidasi = Peminjaman::where('status', 'menunggu')->count();

        $terlambat = Peminjaman::where('status', 'dipinjam')
            ->whereDate('tanggal_jatuh_tempo', '<', now()->toDateString())
            ->count();

        $totalDendaBelum = (int) Denda::where('status_bayar', 'belum')->sum('total_denda');

        $pengajuanTerbaru = Peminjaman::with(['anggota', 'detail.buku'])
            ->where('status', 'menunggu')
            ->oldest('created_at')
            ->take(5)
            ->get();

        $notifikasiTerbaru = auth('admin')->user()
            ->notifikasi()
            ->latest('created_at')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalAnggotaAktif', 'anggotaMenunggu', 'pinjamanAktif', 'menungguValidasi',
            'terlambat', 'totalDendaBelum', 'pengajuanTerbaru', 'notifikasiTerbaru'
        ));
    }
}
