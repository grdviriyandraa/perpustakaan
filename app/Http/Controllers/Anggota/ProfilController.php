<?php

namespace App\Http\Controllers\Anggota;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdatePasswordRequest;
use App\Http\Requests\UpdateProfilRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProfilController extends Controller
{
    public function index(): View
    {
        return view('anggota.profil.index', ['anggota' => auth('anggota')->user()]);
    }

    public function update(UpdateProfilRequest $request): RedirectResponse
    {
        auth('anggota')->user()->update($request->validated());

        return redirect()->route('anggota.profil')->with('success', 'Profil berhasil diperbarui.');
    }

    public function updatePassword(UpdatePasswordRequest $request): RedirectResponse
    {
        auth('anggota')->user()->update(['password' => $request->input('password')]);

        return redirect()->route('anggota.profil')->with('success', 'Password berhasil diubah.');
    }
}
