<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Slide;
use App\Models\HomeSetting;
use App\Models\AboutSetting;
use App\Models\ContactSetting;

class HomeController extends Controller
{
    /**
     * Display the homepage.
     */
    public function index()
    {
        $slides = Slide::where('active', true)->orderBy('position')->get();
        return view('frontend.home.index', compact('slides'));
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
