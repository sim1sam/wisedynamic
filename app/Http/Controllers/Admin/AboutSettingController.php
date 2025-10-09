<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AboutSettingController extends Controller
{
    /**
     * Show the form for editing the about page settings.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        $aboutSetting = \App\Models\AboutSetting::first() ?? new \App\Models\AboutSetting();
        return view('admin.settings.about.edit', compact('aboutSetting'));
    }

    /**
     * Update the about page settings.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'who_we_are_content' => 'nullable|string',
            'who_we_are_image' => 'nullable|string',
            'cta_title' => 'nullable|string|max:255',
            'cta_subtitle' => 'nullable|string|max:255',
            'cta_button_text' => 'nullable|string|max:255',
        ]);

        // Handle JSON fields
        $aboutItems = [];
        if ($request->has('about_items')) {
            foreach ($request->about_items as $key => $item) {
                if (!empty($item['title']) || !empty($item['text']) || !empty($item['icon'])) {
                    $aboutItems[] = [
                        'title' => $item['title'] ?? '',
                        'text' => $item['text'] ?? '',
                        'icon' => $item['icon'] ?? 'fas fa-check',
                    ];
                }
            }
        }
        $validated['about_items'] = $aboutItems;

        // Handle stats
        $stats = [];
        if ($request->has('stats')) {
            foreach ($request->stats as $key => $item) {
                if (!empty($item['value']) || !empty($item['label'])) {
                    $stats[] = [
                        'value' => $item['value'] ?? '',
                        'label' => $item['label'] ?? '',
                    ];
                }
            }
        }
        $validated['stats'] = $stats;

        // Handle values
        $values = [];
        if ($request->has('values')) {
            foreach ($request->values as $key => $item) {
                if (!empty($item['title']) || !empty($item['description'])) {
                    $values[] = [
                        'title' => $item['title'] ?? '',
                        'description' => $item['description'] ?? '',
                    ];
                }
            }
        }
        $validated['values'] = $values;

        // Handle services
        $services = [];
        if ($request->has('services')) {
            foreach ($request->services as $key => $item) {
                if (!empty($item['title']) || !empty($item['description'])) {
                    $services[] = [
                        'title' => $item['title'] ?? '',
                        'description' => $item['description'] ?? '',
                    ];
                }
            }
        }
        $validated['services'] = $services;

        // Save image if uploaded
        if ($request->hasFile('who_we_are_image_file')) {
            $file = $request->file('who_we_are_image_file');
            $filename = time() . '_about_image.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/about'), $filename);
            $validated['who_we_are_image'] = '/images/about/' . $filename;
        }

        $aboutSetting = \App\Models\AboutSetting::first();
        if ($aboutSetting) {
            $aboutSetting->update($validated);
        } else {
            \App\Models\AboutSetting::create($validated);
        }

        return redirect()->route('admin.settings.about.edit')
            ->with('success', 'About page settings updated successfully.');
    }
}
