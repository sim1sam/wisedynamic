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
        
        return view('frontend.home.index', compact('slides', 'homeSetting', 'categories', 'featuredServices', 'webDevPackages'));
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
}
