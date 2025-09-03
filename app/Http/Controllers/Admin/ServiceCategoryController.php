<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ServiceCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = ServiceCategory::all();
        return view('admin.service-categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.service-categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            \Log::info('ServiceCategory store method called', ['request' => $request->all()]);
            
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'icon' => 'required|string|max:255',
                'description' => 'required|string',
                'status' => 'boolean',
            ]);

            $validated['slug'] = Str::slug($validated['name']);
            $validated['status'] = $request->has('status');

            \Log::info('Validated data', ['data' => $validated]);
            
            $category = ServiceCategory::create($validated);
            
            \Log::info('ServiceCategory created', ['category_id' => $category->id]);

            return redirect()->route('service-categories.index')
                ->with('success', 'Service category created successfully.');
        } catch (\Exception $e) {
            \Log::error('Error creating service category', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Failed to create service category: ' . $e->getMessage()]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ServiceCategory $service_category)
    {
        return view('admin.service-categories.edit', ['serviceCategory' => $service_category]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ServiceCategory $service_category)
    {
        try {
            \Log::info('ServiceCategory update method called', [
                'request' => $request->all(),
                'category_id' => $service_category->id
            ]);
            
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'icon' => 'required|string|max:255',
                'description' => 'required|string',
                'status' => 'boolean',
            ]);

            $validated['slug'] = Str::slug($validated['name']);
            $validated['status'] = $request->has('status');

            \Log::info('Validated data for update', ['data' => $validated]);
            
            $service_category->update($validated);
            
            \Log::info('ServiceCategory updated', ['category_id' => $service_category->id]);

            return redirect()->route('service-categories.index')
                ->with('success', 'Service category updated successfully.');
        } catch (\Exception $e) {
            \Log::error('Error updating service category', [
                'error' => $e->getMessage(),
                'category_id' => $service_category->id,
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Failed to update service category: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ServiceCategory $service_category)
    {
        // Check if category has services
        if ($service_category->services()->count() > 0) {
            return redirect()->route('service-categories.index')
                ->with('error', 'Cannot delete category with associated services.');
        }

        $service_category->delete();

        return redirect()->route('service-categories.index')
            ->with('success', 'Service category deleted successfully.');
    }
}
