<?php

namespace App\Http\Controllers\Anggota;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\Wishlist;
use Illuminate\Http\RedirectResponse;

class WishlistController extends Controller
{
    public function toggle(Buku $buku): RedirectResponse
    {
        $anggota = auth('anggota')->user();

        $wishlist = Wishlist::where('anggota_id', $anggota->id)
            ->where('buku_id', $buku->id)
            ->first();

        if ($wishlist) {
            $wishlist->delete();
            $pesan = 'Buku dihapus dari wishlist.';
        } else {
            Wishlist::create(['anggota_id' => $anggota->id, 'buku_id' => $buku->id]);
            $pesan = 'Buku ditambahkan ke wishlist.';
        }

        return back()->with('success', $pesan);
    }
}
