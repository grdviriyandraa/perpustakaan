<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class BantuanController extends Controller
{
    public function publik(): View
    {
        return view('bantuan.publik');
    }

    public function anggota(): View
    {
        return view('anggota.bantuan.index');
    }

    public function admin(): View
    {
        return view('admin.bantuan.index');
    }
}
