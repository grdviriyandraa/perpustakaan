<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthAnggota
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! auth('anggota')->check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        if (auth('anggota')->user()->status_akun !== 'aktif') {
            auth('anggota')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->with('error', 'Akun belum aktif atau ditolak. Hubungi admin perpustakaan.');
        }

        return $next($request);
    }
}
