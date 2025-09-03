<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Slide;
use App\Models\HomeSetting;
use App\Models\AboutSetting;
use App\Models\ContactSetting;
use App\Models\Service;
use App\Models\ServiceCategory;

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
        
        return view('frontend.home.index', compact('slides', 'homeSetting', 'categories', 'featuredServices'));
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
}
