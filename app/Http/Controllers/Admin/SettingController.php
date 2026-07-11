<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateSettingRequest;
use App\Models\SystemSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function index(): View
    {
        $settings = SystemSetting::orderBy('id')->get()->keyBy('key');

        return view('admin.setting.index', compact('settings'));
    }

    public function update(UpdateSettingRequest $request): RedirectResponse
    {
        foreach ($request->validated() as $key => $value) {
            SystemSetting::where('key', $key)->update(['value' => (string) $value]);
        }

        return redirect()->route('admin.setting.index')->with('success', 'Pengaturan sistem tersimpan.');
    }
}
