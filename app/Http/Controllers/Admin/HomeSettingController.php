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
            // About section
            'about_title' => 'required|string|max:255',
            'about_subtitle' => 'required|string',
            'about_items' => 'required|array',
            'about_items.*.icon' => 'required|string',
            'about_items.*.title' => 'required|string|max:255',
            'about_items.*.text' => 'required|string',
            
            // Why Choose Us section
            'why_choose_title' => 'required|string|max:255',
            'why_choose_subtitle' => 'required|string',
            'why_choose_items' => 'required|array',
            'why_choose_items.*.icon' => 'required|string',
            'why_choose_items.*.title' => 'required|string|max:255',
            'why_choose_items.*.text' => 'required|string',
            'why_choose_clients_count' => 'required|integer|min:1',
            'why_choose_experience' => 'required|string|max:255',
            
            // Let's Build Something Amazing section
            'contact_title' => 'required|string|max:255',
            'contact_subtitle' => 'required|string',
            'contact_phone' => 'required|string|max:255',
            'contact_whatsapp' => 'nullable|string|max:255',
            'contact_email' => 'required|email|max:255',
            'contact_location' => 'required|string|max:255',
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
