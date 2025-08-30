<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WebsiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class WebsiteSettingController extends Controller
{
    /**
     * Show the form for editing the website settings.
     */
    public function edit()
    {
        $websiteSetting = WebsiteSetting::first() ?? new WebsiteSetting();
        return view('admin.settings.website', compact('websiteSetting'));
    }

    /**
     * Update the website settings.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'site_name' => 'required|string|max:255',
            'site_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'site_favicon' => 'nullable|image|mimes:ico,png|max:1024',
            'logo_alt_text' => 'nullable|string|max:255',
        ]);
        
        // Handle checkbox separately since unchecked checkboxes don't send any value
        $validated['show_site_name_with_logo'] = $request->has('show_site_name_with_logo');

        $websiteSetting = WebsiteSetting::first() ?? new WebsiteSetting();
        
        // Handle logo upload
        if ($request->hasFile('site_logo')) {
            // Delete old logo if exists
            if ($websiteSetting->site_logo) {
                Storage::disk('public')->delete($websiteSetting->site_logo);
            }
            
            $logoPath = $request->file('site_logo')->store('logos', 'public');
            $validated['site_logo'] = $logoPath;
        }
        
        // Handle favicon upload
        if ($request->hasFile('site_favicon')) {
            // Delete old favicon if exists
            if ($websiteSetting->site_favicon) {
                Storage::disk('public')->delete($websiteSetting->site_favicon);
            }
            
            $faviconPath = $request->file('site_favicon')->store('logos', 'public');
            $validated['site_favicon'] = $faviconPath;
        }
        
        $websiteSetting->fill($validated);
        $websiteSetting->save();
        
        return redirect()->route('admin.settings.website')
            ->with('success', 'Website settings updated successfully!');
    }
}
