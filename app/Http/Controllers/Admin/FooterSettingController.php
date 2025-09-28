<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FooterSetting;
use Illuminate\Http\Request;

class FooterSettingController extends Controller
{
    public function edit()
    {
        $setting = FooterSetting::query()->latest('id')->first();
        return view('admin.settings.footer', [
            'setting' => $setting,
        ]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'company_name' => ['nullable','string','max:255'],
            'tagline' => ['nullable','string','max:255'],
            'phone' => ['nullable','string','max:255'],
            'email' => ['nullable','email','max:255'],
            'facebook_url' => ['nullable','url','max:2048'],
            'twitter_url' => ['nullable','url','max:2048'],
            'linkedin_url' => ['nullable','url','max:2048'],
            'instagram_url' => ['nullable','url','max:2048'],
            'copyright_text' => ['nullable','string','max:255'],
            'ssl_logo' => ['nullable','image','mimes:jpeg,png,jpg,gif,svg','max:2048'],
        ]);

        // Handle SSL logo upload
        if ($request->hasFile('ssl_logo')) {
            $file = $request->file('ssl_logo');
            $filename = time() . '_ssl_logo.' . $file->getClientOriginalExtension();
            $file->move(public_path('images'), $filename);
            $data['ssl_logo'] = 'images/' . $filename;
        }

        $setting = FooterSetting::query()->latest('id')->first();
        if ($setting) {
            // If updating and new SSL logo uploaded, delete old one
            if ($request->hasFile('ssl_logo') && $setting->ssl_logo && file_exists(public_path($setting->ssl_logo))) {
                unlink(public_path($setting->ssl_logo));
            }
            $setting->update($data);
        } else {
            $setting = FooterSetting::create($data);
        }

        return redirect()->route('admin.settings.footer.edit')->with('success','Footer settings saved');
    }
}
