<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PackageCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PackageCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $categories = PackageCategory::all();
            return view('admin.package-categories.index', compact('categories'));
        } catch (\Exception $e) {
            Log::error('Error displaying package categories: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while loading package categories.');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.package-categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'icon' => 'required|string|max:255',
                'description' => 'required|string',
            ]);
            
            $packageCategory = new PackageCategory();
            $packageCategory->name = $validated['name'];
            $packageCategory->slug = Str::slug($validated['name']);
            $packageCategory->icon = $validated['icon'];
            $packageCategory->description = $validated['description'];
            $packageCategory->status = $request->has('status');
            
            $packageCategory->save();
            
            return redirect()->route('package-categories.index')
                ->with('success', 'Package category created successfully.');
        } catch (\Exception $e) {
            Log::error('Error creating package category: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'An error occurred while creating the package category.')
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Not needed for admin panel
        return redirect()->route('package-categories.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PackageCategory $packageCategory)
    {
        try {
            return view('admin.package-categories.edit', compact('packageCategory'));
        } catch (\Exception $e) {
            Log::error('Error loading package category for edit: ' . $e->getMessage());
            return redirect()->route('package-categories.index')
                ->with('error', 'Package category not found.');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PackageCategory $packageCategory)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'icon' => 'required|string|max:255',
                'description' => 'required|string',
            ]);
            
            $packageCategory->name = $validated['name'];
            // Only update slug if name has changed
            if ($packageCategory->name !== $validated['name']) {
                $packageCategory->slug = Str::slug($validated['name']);
            }
            $packageCategory->icon = $validated['icon'];
            $packageCategory->description = $validated['description'];
            $packageCategory->status = $request->has('status');
            
            $packageCategory->save();
            
            return redirect()->route('package-categories.index')
                ->with('success', 'Package category updated successfully.');
        } catch (\Exception $e) {
            Log::error('Error updating package category: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'An error occurred while updating the package category.')
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PackageCategory $packageCategory)
    {
        try {
            // Check if category has packages
            if ($packageCategory->packages()->count() > 0) {
                return redirect()->route('package-categories.index')
                    ->with('error', 'Cannot delete category because it has associated packages.');
            }
            
            $packageCategory->delete();
            
            return redirect()->route('package-categories.index')
                ->with('success', 'Package category deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Error deleting package category: ' . $e->getMessage());
            return redirect()->route('package-categories.index')
                ->with('error', 'An error occurred while deleting the package category.');
        }
    }
}
