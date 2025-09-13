<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\PackageCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PackageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $packages = Package::with('category')->get();
        return view('admin.packages.index', compact('packages'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = PackageCategory::where('status', true)->get();
        return view('admin.packages.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            Log::info('Package store method called', ['request' => $request->all()]);
            
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'short_description' => 'required|string',
                'description' => 'required|string',
                'price' => 'nullable|numeric',
                'price_unit' => 'nullable|string|max:50',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'package_category_id' => 'required|exists:package_categories,id',
            ]);

            $validated['slug'] = Str::slug($validated['title']);
            $validated['status'] = $request->has('status');
            $validated['featured'] = $request->has('featured');

            // Handle image upload
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('packages', 'public');
                $validated['image'] = $imagePath;
                Log::info('Image uploaded', ['path' => $imagePath]);
            }

            Log::info('Validated data', ['data' => $validated]);
            
            $package = Package::create($validated);
            
            Log::info('Package created', ['package_id' => $package->id]);

            return redirect()->route('admin.packages.index')
                ->with('success', 'Package created successfully.');
        } catch (\Exception $e) {
            Log::error('Error creating package', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Failed to create package: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Package $package)
    {
        return redirect()->route('admin.packages.edit', $package);
    }
    
    /**
     * Display the specified package by slug.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function showBySlug($slug)
    {
        try {
            $package = Package::where('slug', $slug)->firstOrFail();
            return view('admin.packages.show', compact('package'));
        } catch (\Exception $e) {
            Log::error('Error finding package by slug', [
                'slug' => $slug,
                'error' => $e->getMessage()
            ]);
            
            return redirect()->route('admin.packages.index')
                ->with('error', 'Package not found: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Package $package)
    {
        $categories = PackageCategory::where('status', true)->get();
        return view('admin.packages.edit', compact('package', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Package $package)
    {
        try {
            Log::info('Package update method called', [
                'request' => $request->all(),
                'package_id' => $package->id
            ]);
            
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'short_description' => 'required|string',
                'description' => 'required|string',
                'price' => 'nullable|numeric',
                'price_unit' => 'nullable|string|max:50',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'package_category_id' => 'required|exists:package_categories,id',
            ]);

            $validated['slug'] = Str::slug($validated['title']);
            $validated['status'] = $request->has('status');
            $validated['featured'] = $request->has('featured');

            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($package->image) {
                    Storage::disk('public')->delete($package->image);
                }
                
                $imagePath = $request->file('image')->store('packages', 'public');
                $validated['image'] = $imagePath;
                Log::info('Image updated', ['path' => $imagePath]);
            }

            Log::info('Validated data for update', ['data' => $validated]);
            
            $package->update($validated);
            
            Log::info('Package updated', ['package_id' => $package->id]);

            return redirect()->route('admin.packages.index')
                ->with('success', 'Package updated successfully.');
        } catch (\Exception $e) {
            Log::error('Error updating package', [
                'error' => $e->getMessage(),
                'package_id' => $package->id,
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Failed to update package: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Package $package)
    {
        try {
            // Delete image if exists
            if ($package->image) {
                Storage::disk('public')->delete($package->image);
            }
            
            $package->delete();
            
            return redirect()->route('admin.packages.index')
                ->with('success', 'Package deleted successfully');
        } catch (\Exception $e) {
            Log::error('Error deleting package', [
                'error' => $e->getMessage(),
                'package_id' => $package->id
            ]);
            
            return redirect()->route('admin.packages.index')
                ->with('error', 'Failed to delete package: ' . $e->getMessage());
        }
    }
    
    /**
     * Display a listing of Website Development packages.
     */
    public function websiteDevelopment()
    {
        try {
            // Find the Website Development category
            $category = PackageCategory::where('name', 'Website Development')->first();
            
            if (!$category) {
                return redirect()->route('admin.packages.index')
                    ->with('error', 'Website Development category not found.');
            }
            
            $packages = Package::where('package_category_id', $category->id)->get();
            
            return view('admin.packages.website-development', [
                'packages' => $packages,
                'category' => $category
            ]);
        } catch (\Exception $e) {
            Log::error('Error loading website development packages', [
                'error' => $e->getMessage()
            ]);
            
            return redirect()->route('admin.packages.index')
                ->with('error', 'Failed to load Website Development packages: ' . $e->getMessage());
        }
    }
    
    /**
     * Display a listing of Digital Marketing packages.
     */
    public function digitalMarketing()
    {
        try {
            // Find the Digital Marketing category
            $category = PackageCategory::where('name', 'Digital Marketing')->first();
            
            if (!$category) {
                return redirect()->route('admin.packages.index')
                    ->with('error', 'Digital Marketing category not found.');
            }
            
            $packages = Package::where('package_category_id', $category->id)->get();
            
            return view('admin.packages.digital-marketing', [
                'packages' => $packages,
                'category' => $category
            ]);
        } catch (\Exception $e) {
            Log::error('Error loading digital marketing packages', [
                'error' => $e->getMessage()
            ]);
            
            return redirect()->route('admin.packages.index')
                ->with('error', 'Failed to load Digital Marketing packages: ' . $e->getMessage());
        }
    }
}
