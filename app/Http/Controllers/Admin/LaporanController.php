<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Anggota;
use App\Models\Buku;
use App\Models\Denda;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class LaporanController extends Controller
{
    public function index(Request $request): View
    {
        $data = $this->kumpulkanData($request);

        return view('admin.laporan.index', $data);
    }

    public function exportPdf(Request $request): Response
    {
        $data = $this->kumpulkanData($request);
        $data['dicetak'] = now();

        $pdf = Pdf::loadView('print.laporan', $data)->setPaper('a4', 'landscape');

        return $pdf->download('laporan-peminjaman-'.$data['mulai']->format('Ymd').'-'.$data['sampai']->format('Ymd').'.pdf');
    }

    /**
     * @return array<string, mixed>
     */
    private function kumpulkanData(Request $request): array
    {
        $mulai = $request->query('mulai')
            ? Carbon::parse($request->query('mulai'))->startOfDay()
            : now()->startOfMonth();
        $sampai = $request->query('sampai')
            ? Carbon::parse($request->query('sampai'))->endOfDay()
            : now()->endOfMonth();

        $rentang = [$mulai->toDateString(), $sampai->toDateString()];

        $totalPeminjaman = Peminjaman::whereBetween('tanggal_pengajuan', $rentang)->count();

        $totalTerlambat = Pengembalian::whereBetween('tanggal_pengembalian', $rentang)
            ->where('status_pengembalian', 'terlambat')
            ->count();

        $totalDenda = (int) Denda::whereHas(
            'pengembalian',
            fn ($q) => $q->whereBetween('tanggal_pengembalian', $rentang)
        )->sum('total_denda');

        $bukuPopuler = Buku::select('buku.judul', 'buku.penulis')
            ->selectRaw('COUNT(detail_peminjaman.id) as total_pinjam')
            ->join('detail_peminjaman', 'detail_peminjaman.buku_id', '=', 'buku.id')
            ->join('peminjaman', 'peminjaman.id', '=', 'detail_peminjaman.peminjaman_id')
            ->whereBetween('peminjaman.tanggal_pengajuan', $rentang)
            ->whereNotIn('peminjaman.status', ['ditolak'])
            ->groupBy('buku.id', 'buku.judul', 'buku.penulis')
            ->orderByDesc('total_pinjam')
            ->limit(10)
            ->get();

        $anggotaAktif = Anggota::select('anggota.nama_lengkap', 'anggota.email')
            ->selectRaw('COUNT(peminjaman.id) as total_pinjam')
            ->join('peminjaman', 'peminjaman.anggota_id', '=', 'anggota.id')
            ->whereBetween('peminjaman.tanggal_pengajuan', $rentang)
            ->whereNotIn('peminjaman.status', ['ditolak'])
            ->groupBy('anggota.id', 'anggota.nama_lengkap', 'anggota.email')
            ->orderByDesc('total_pinjam')
            ->limit(10)
            ->get();

        $chartData = Peminjaman::selectRaw('DATE(tanggal_pengajuan) as tanggal, COUNT(*) as total')
            ->whereBetween('tanggal_pengajuan', $rentang)
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->get();

        return compact(
            'mulai', 'sampai', 'totalPeminjaman', 'totalTerlambat',
            'totalDenda', 'bukuPopuler', 'anggotaAktif', 'chartData'
        );
    }
}
