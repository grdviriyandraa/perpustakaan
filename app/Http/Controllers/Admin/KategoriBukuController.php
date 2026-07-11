<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreKategoriRequest;
use App\Models\KategoriBuku;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class KategoriBukuController extends Controller
{
    public function index(Request $request): View
    {
        $kategori = KategoriBuku::withCount('buku')->orderBy('nama_kategori')->get();
        $edit = $request->query('edit')
            ? KategoriBuku::find($request->query('edit'))
            : null;

        return view('admin.kategori.index', compact('kategori', 'edit'));
    }

    public function store(StoreKategoriRequest $request): RedirectResponse
    {
        KategoriBuku::create($request->validated());

        return redirect()->route('admin.kategori.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function update(StoreKategoriRequest $request, KategoriBuku $kategori): RedirectResponse
    {
        $kategori->update($request->validated());

        return redirect()->route('admin.kategori.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(KategoriBuku $kategori): RedirectResponse
    {
        if ($kategori->buku()->exists()) {
            return redirect()
                ->route('admin.kategori.index')
                ->with('error', 'Kategori masih dipakai buku, tidak dapat dihapus.');
        }

        $kategori->delete();

        return redirect()->route('admin.kategori.index')->with('success', 'Kategori dihapus.');
    }
}
