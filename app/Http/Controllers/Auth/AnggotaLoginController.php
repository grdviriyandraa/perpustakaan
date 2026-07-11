<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AnggotaLoginController extends Controller
{
    public function showLogin(): View|RedirectResponse
    {
        if (auth('anggota')->check()) {
            return redirect()->route('anggota.dashboard');
        }

        return view('auth.login');
    }

    public function login(LoginRequest $request): RedirectResponse
    {
        if (! auth('anggota')->attempt($request->credentials(), $request->boolean('remember'))) {
            return back()
                ->withInput($request->only('login'))
                ->with('error', 'Email/username atau password salah.');
        }

        $anggota = auth('anggota')->user();

        if ($anggota->status_akun !== 'aktif') {
            auth('anggota')->logout();

            $pesan = match ($anggota->status_akun) {
                'menunggu' => 'Akun Anda masih menunggu verifikasi admin.',
                'ditolak' => 'Pendaftaran Anda ditolak. Hubungi admin perpustakaan.',
                default => 'Akun Anda dinonaktifkan. Hubungi admin perpustakaan.',
            };

            return back()->withInput($request->only('login'))->with('error', $pesan);
        }

        $request->session()->regenerate();

        return redirect()->intended(route('anggota.dashboard'));
    }

    public function logout(Request $request): RedirectResponse
    {
        auth('anggota')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda telah keluar.');
    }
}
