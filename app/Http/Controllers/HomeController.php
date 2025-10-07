<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Slide;
use App\Models\HomeSetting;
use App\Models\AboutSetting;
use App\Models\ContactSetting;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\Package;
use App\Models\PackageCategory;

class HomeController extends Controller
{
    /**
     * Display the homepage.
     */
    public function index()
    {
        $slides = Slide::where('active', true)->orderBy('position')->get();
        $homeSetting = HomeSetting::first() ?? new HomeSetting();
        $contactSetting = ContactSetting::first() ?? new ContactSetting();
        
        // Get service categories for homepage
        $categories = ServiceCategory::where('status', true)->get();
        
        // Also get featured services for other sections if needed
        $featuredServices = Service::where('status', true)
            ->where('featured', true)
            ->with('category')
            ->take(6)
            ->get();
            
        // Get website development packages for homepage
        $webDevCategory = PackageCategory::where('name', 'Website Development')->first();
        $webDevPackages = [];
        
        if ($webDevCategory) {
            $webDevPackages = Package::where('package_category_id', $webDevCategory->id)
                ->where('status', true)
                ->orderBy('price')
                ->take(4) // Limit to 4 packages for homepage display
                ->get();
        }
        
        // Get digital marketing packages for homepage
        $marketingCategory = PackageCategory::where('name', 'Digital Marketing')->first();
        $marketingPackages = [];
        
        if ($marketingCategory) {
            $marketingPackages = Package::where('package_category_id', $marketingCategory->id)
                ->where('status', true)
                ->orderBy('price')
                ->take(3) // Limit to 3 packages for homepage display
                ->get();
        }
        
        return view('frontend.home.index', compact('slides', 'homeSetting', 'contactSetting', 'categories', 'featuredServices', 'webDevPackages', 'marketingPackages'));
    }
    
    /**
     * Display the about page.
     */
    public function about()
    {
        $aboutSetting = AboutSetting::first() ?? new AboutSetting();
        return view('frontend.about.index', compact('aboutSetting'));
    }
    
    /**
     * Display the contact page.
     */
    public function contact()
    {
        $contactSetting = ContactSetting::first() ?? new ContactSetting();
        return view('frontend.contact.index', compact('contactSetting'));
    }
    
    /**
     * Display the packages page.
     */
    public function packages()
    {
        // Get website development category
        $webDevCategory = PackageCategory::where('name', 'Website Development')->first();
        
        // Get digital marketing category
        $marketingCategory = PackageCategory::where('name', 'Digital Marketing')->first();
        
        // Get active packages for each category
        $webDevPackages = [];
        $marketingPackages = [];
        
        if ($webDevCategory) {
            $webDevPackages = Package::where('package_category_id', $webDevCategory->id)
                ->where('status', true)
                ->orderBy('price')
                ->get();
        }
        
        if ($marketingCategory) {
            $marketingPackages = Package::where('package_category_id', $marketingCategory->id)
                ->where('status', true)
                ->orderBy('price')
                ->get();
        }
        
        return view('frontend.packages.index', compact('webDevPackages', 'marketingPackages'));
    }
    
    /**
     * Display individual package details by slug.
     *
     * @param string $slug
     * @return \Illuminate\Http\Response
     */
    public function showPackage($slug)
    {
        // Find the package by slug
        $package = Package::where('slug', $slug)
            ->where('status', true)
            ->with('category')
            ->firstOrFail();
        
        // Get related packages in the same category
        $relatedPackages = Package::where('package_category_id', $package->package_category_id)
            ->where('id', '!=', $package->id)
            ->where('status', true)
            ->take(3)
            ->get();
        
        return view('frontend.packages.show', compact('package', 'relatedPackages'));
    }
}
