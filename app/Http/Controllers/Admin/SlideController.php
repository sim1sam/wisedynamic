<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Slide;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SlideController extends Controller
{
    public function index()
    {
        $slides = Slide::orderBy('position')->get();
        return view('admin.slides.index', compact('slides'));
    }

    public function create()
    {
        return view('admin.slides.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required','string','max:255'],
            'subtitle' => ['nullable','string','max:255'],
            'price_text' => ['nullable','string','max:255'],
            'link_url' => ['nullable','url','max:2048'],
            'image_source' => ['required','in:url,upload'],
            'image_url' => ['nullable','url','max:2048'],
            'image_file' => ['nullable','image','max:4096'],
            'position' => ['nullable','integer','min:0'],
            // Change validation rule to accept checkbox value properly
            'active' => ['sometimes'],
        ]);

        $payload = [
            'title' => $data['title'],
            'subtitle' => $data['subtitle'] ?? null,
            'price_text' => $data['price_text'] ?? null,
            'link_url' => $data['link_url'] ?? null,
            'image_source' => $data['image_source'],
            'image_url' => $data['image_source'] === 'url' ? ($data['image_url'] ?? null) : null,
            'position' => $data['position'] ?? 0,
            // Fix for active field: use request->has() to check if checkbox was submitted
            'active' => $request->has('active'),
        ];

        if ($data['image_source'] === 'upload' && $request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $filename = time() . '_slide.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/slides'), $filename);
            $payload['image_path'] = 'images/slides/' . $filename;
        }

        Slide::create($payload);

        return redirect()->route('admin.slides.index')->with('success','Slide created');
    }

    public function edit(Slide $slide)
    {
        return view('admin.slides.edit', compact('slide'));
    }

    public function update(Request $request, Slide $slide)
    {
        $data = $request->validate([
            'title' => ['required','string','max:255'],
            'subtitle' => ['nullable','string','max:255'],
            'price_text' => ['nullable','string','max:255'],
            'link_url' => ['nullable','url','max:2048'],
            'image_source' => ['required','in:url,upload'],
            'image_url' => ['nullable','url','max:2048'],
            'image_file' => ['nullable','image','max:4096'],
            'position' => ['nullable','integer','min:0'],
            // Change validation rule to accept checkbox value properly
            'active' => ['sometimes'],
        ]);

        $slide->title = $data['title'];
        $slide->subtitle = $data['subtitle'] ?? null;
        $slide->price_text = $data['price_text'] ?? null;
        $slide->link_url = $data['link_url'] ?? null;
        $slide->position = $data['position'] ?? 0;
        // Fix for active field: use request->has() to check if checkbox was submitted
        $slide->active = $request->has('active');
        $slide->image_source = $data['image_source'];

        if ($data['image_source'] === 'url') {
            $slide->image_url = $data['image_url'] ?? null;
            // keep existing image_path if set, unless switching source wipes it
        } else {
            $slide->image_url = null;
            if ($request->hasFile('image_file')) {
                if ($slide->image_path && file_exists(public_path($slide->image_path))) {
                    unlink(public_path($slide->image_path));
                }
                $file = $request->file('image_file');
                $filename = time() . '_slide.' . $file->getClientOriginalExtension();
                $file->move(public_path('images/slides'), $filename);
                $slide->image_path = 'images/slides/' . $filename;
            }
        }

        $slide->save();

        return redirect()->route('admin.slides.index')->with('success','Slide updated');
    }

    public function destroy(Slide $slide)
    {
        if ($slide->image_path && file_exists(public_path($slide->image_path))) {
            unlink(public_path($slide->image_path));
        }
        $slide->delete();
        return redirect()->route('admin.slides.index')->with('success','Slide deleted');
    }
    
    /**
     * Toggle the active status of a slide.
     */
    public function toggleActive(Slide $slide)
    {
        $slide->active = !$slide->active;
        $slide->save();
        
        return redirect()->route('admin.slides.index')
            ->with('success', 'Slide ' . ($slide->active ? 'activated' : 'deactivated'));
    }
}
