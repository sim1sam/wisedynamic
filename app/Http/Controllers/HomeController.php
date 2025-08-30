<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Slide;
use App\Models\HomeSetting;

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
        $homeSetting = HomeSetting::first() ?? new HomeSetting();
        return view('frontend.about.index', compact('homeSetting'));
    }
}
