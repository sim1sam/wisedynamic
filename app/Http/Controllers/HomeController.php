<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Slide;

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
}
