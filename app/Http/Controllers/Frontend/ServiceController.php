<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    /**
     * Display a listing of services by category.
     *
     * @param  string|null  $category
     * @return \Illuminate\Http\Response
     */
    public function index($category = null)
    {
        $categories = \App\Models\ServiceCategory::where('status', true)->get();
        
        if ($category) {
            $activeCategory = \App\Models\ServiceCategory::where('slug', $category)->firstOrFail();
            $services = \App\Models\Service::where('service_category_id', $activeCategory->id)
                ->where('status', true)
                ->paginate(9);
        } else {
            $activeCategory = null;
            $services = \App\Models\Service::where('status', true)
                ->paginate(9);
        }
        
        return view('frontend.services.index', compact('categories', 'services', 'activeCategory'));
    }
    
    /**
     * Display the specified service.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $service = \App\Models\Service::where('slug', $slug)->where('status', true)->firstOrFail();
        $relatedServices = \App\Models\Service::where('service_category_id', $service->service_category_id)
            ->where('id', '!=', $service->id)
            ->where('status', true)
            ->limit(3)
            ->get();
            
        return view('frontend.services.show', compact('service', 'relatedServices'));
    }
}
