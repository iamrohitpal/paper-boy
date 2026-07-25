<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    public function index()
    {
        $setting = Setting::firstOrCreate([], [
            'app_name' => 'Newspaper Distributor',
            'primary_color' => '#4f46e5',
        ]);

        return view('settings.index', compact('setting'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'app_name' => 'required|string|max:255',
            'primary_color' => 'required|string|max:7',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
        ]);

        $setting = Setting::first();

        $data = [
            'app_name' => $request->app_name,
            'primary_color' => $request->primary_color,
        ];

        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($setting->logo_path) {
                Storage::disk('public')->delete($setting->logo_path);
            }
            $data['logo_path'] = $request->file('logo')->store('settings', 'public');
        }

        $setting->update($data);

        return redirect()->route('settings.index')->with('success', 'Settings updated successfully.');
    }
}
