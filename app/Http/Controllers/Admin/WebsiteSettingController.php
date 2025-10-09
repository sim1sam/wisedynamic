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
            'meta_title' => 'nullable|string|max:60',
            'meta_description' => 'nullable|string|max:160',
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
            if ($websiteSetting->site_logo && file_exists(public_path($websiteSetting->site_logo))) {
                unlink(public_path($websiteSetting->site_logo));
            }
            
            $file = $request->file('site_logo');
            $filename = time() . '_site_logo.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/logos'), $filename);
            $validated['site_logo'] = 'images/logos/' . $filename;
        }
        
        // Handle favicon upload
        if ($request->hasFile('site_favicon')) {
            // Delete old favicon if exists
            if ($websiteSetting->site_favicon && file_exists(public_path($websiteSetting->site_favicon))) {
                unlink(public_path($websiteSetting->site_favicon));
            }
            
            $file = $request->file('site_favicon');
            $filename = time() . '_favicon.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/logos'), $filename);
            $validated['site_favicon'] = 'images/logos/' . $filename;
        }
        
        $websiteSetting->fill($validated);
        $websiteSetting->save();
        
        return redirect()->route('admin.settings.website')
            ->with('success', 'Website settings updated successfully!');
    }
}
