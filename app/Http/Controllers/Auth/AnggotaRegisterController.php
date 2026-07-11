<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAnggotaRequest;
use App\Models\Anggota;
use App\Services\NotifikasiService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AnggotaRegisterController extends Controller
{
    public function __construct(private NotifikasiService $notifikasi)
    {
    }

    public function showForm(): View
    {
        return view('auth.register');
    }

    public function store(StoreAnggotaRequest $request): RedirectResponse
    {
        $verifikasiWajib = setting('verifikasi_akun', 'aktif') === 'aktif';

        $anggota = Anggota::create([
            ...$request->validated(),
            'status_akun' => $verifikasiWajib ? 'menunggu' : 'aktif',
            'tanggal_daftar' => now()->toDateString(),
        ]);

        $this->notifikasi->kirimKeAdmin(
            'verifikasi',
            "Pendaftaran anggota baru: {$anggota->nama_lengkap} ({$anggota->email}) menunggu verifikasi."
        );

        $pesan = $verifikasiWajib
            ? 'Registrasi berhasil. Akun Anda akan aktif setelah diverifikasi admin.'
            : 'Registrasi berhasil. Silakan login.';

        return redirect()->route('login')->with('success', $pesan);
    }
}
