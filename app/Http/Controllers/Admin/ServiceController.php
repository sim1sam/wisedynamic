<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $services = Service::with('category')->get();
        return view('admin.services.index', compact('services'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = ServiceCategory::where('status', true)->get();
        return view('admin.services.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'short_description' => 'required|string',
            'description' => 'required|string',
            'price' => 'nullable|numeric',
            'price_unit' => 'nullable|string|max:50',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'service_category_id' => 'required|exists:service_categories,id',
        ]);

        $validated['slug'] = Str::slug($validated['title']);
        $validated['status'] = $request->has('status');
        $validated['featured'] = $request->has('featured');

        // Handle image upload
        if ($request->hasFile('image')) {
            try {
                $path = $request->file('image')->store('services', 'public');
                $validated['image'] = $path;
                
                // Log success information
                \Log::info('Image uploaded successfully during creation', [
                    'path' => $path
                ]);
            } catch (\Exception $e) {
                // Log error information
                \Log::error('Failed to upload image during creation', [
                    'error' => $e->getMessage()
                ]);
                
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['image' => 'Failed to upload image: ' . $e->getMessage()]);
            }
        }

        Service::create($validated);

        return redirect()->route('services.index')
            ->with('success', 'Service created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Service $service)
    {
        $categories = ServiceCategory::where('status', true)->get();
        return view('admin.services.edit', compact('service', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Service $service)
    {
        try {
            \Log::info('Service update method called', [
                'request' => $request->all(),
                'service_id' => $service->id
            ]);
            
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'short_description' => 'required|string',
                'description' => 'required|string',
                'price' => 'nullable|numeric',
                'price_unit' => 'nullable|string|max:50',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'service_category_id' => 'required|exists:service_categories,id',
            ]);

            $validated['slug'] = Str::slug($validated['title']);
            $validated['status'] = $request->has('status');
            $validated['featured'] = $request->has('featured');
            
            \Log::info('Validated data for service update', ['data' => $validated]);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($service->image) {
                Storage::disk('public')->delete($service->image);
            }
            
            try {
                $path = $request->file('image')->store('services', 'public');
                $validated['image'] = $path;
                
                // Log success information
                \Log::info('Image uploaded successfully', [
                    'path' => $path,
                    'service_id' => $service->id
                ]);
            } catch (\Exception $e) {
                // Log error information
                \Log::error('Failed to upload image', [
                    'error' => $e->getMessage(),
                    'service_id' => $service->id
                ]);
                
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['image' => 'Failed to upload image: ' . $e->getMessage()]);
            }
        }

            $service->update($validated);
            
            \Log::info('Service updated successfully', ['service_id' => $service->id]);

            return redirect()->route('services.index')
                ->with('success', 'Service updated successfully.');
        } catch (\Exception $e) {
            \Log::error('Error updating service', [
                'error' => $e->getMessage(),
                'service_id' => $service->id,
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Failed to update service: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Service $service)
    {
        // Delete image if exists
        if ($service->image) {
            Storage::disk('public')->delete($service->image);
        }

        $service->delete();

        return redirect()->route('services.index')
            ->with('success', 'Service deleted successfully.');
    }
}
