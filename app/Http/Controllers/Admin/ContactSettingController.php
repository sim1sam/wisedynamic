<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ContactSettingController extends Controller
{
    /**
     * Show the form for editing the contact page settings.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        $contactSetting = \App\Models\ContactSetting::first() ?? new \App\Models\ContactSetting();
        return view('admin.settings.contact.edit', compact('contactSetting'));
    }

    /**
     * Update the contact page settings.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:255',
            'whatsapp' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'map_embed' => 'nullable|string',
            'form_title' => 'nullable|string|max:255',
            'form_subtitle' => 'nullable|string|max:255',
        ]);

        // Handle office hours
        $officeHours = [];
        if ($request->has('office_hours')) {
            foreach ($request->office_hours as $key => $item) {
                if (!empty($item['day']) || !empty($item['hours'])) {
                    $officeHours[] = [
                        'day' => $item['day'] ?? '',
                        'hours' => $item['hours'] ?? '',
                    ];
                }
            }
        }
        $validated['office_hours'] = $officeHours;

        // Handle social links
        $socialLinks = [];
        if ($request->has('social_links')) {
            foreach ($request->social_links as $key => $item) {
                if (!empty($item['platform']) || !empty($item['url']) || !empty($item['icon'])) {
                    $socialLinks[] = [
                        'platform' => $item['platform'] ?? '',
                        'url' => $item['url'] ?? '',
                        'icon' => $item['icon'] ?? '',
                    ];
                }
            }
        }
        $validated['social_links'] = $socialLinks;

        $contactSetting = \App\Models\ContactSetting::first();
        if ($contactSetting) {
            $contactSetting->update($validated);
        } else {
            \App\Models\ContactSetting::create($validated);
        }

        return redirect()->route('admin.settings.contact.edit')
            ->with('success', 'Contact page settings updated successfully.');
    }
}
