<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomeSetting;
use Illuminate\Http\Request;

class HomeSettingController extends Controller
{
    public function edit()
    {
        $homeSetting = HomeSetting::first() ?? new HomeSetting();
        return view('admin.settings.home', compact('homeSetting'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'about_title' => 'required|string|max:255',
            'about_subtitle' => 'required|string',
            'about_items' => 'required|array',
            'about_items.*.icon' => 'required|string',
            'about_items.*.title' => 'required|string|max:255',
            'about_items.*.text' => 'required|string',
        ]);

        $homeSetting = HomeSetting::first();
        
        if ($homeSetting) {
            $homeSetting->update($validated);
        } else {
            HomeSetting::create($validated);
        }

        return redirect()->route('admin.settings.home.edit')->with('success', 'Home settings updated successfully!');
    }
}
