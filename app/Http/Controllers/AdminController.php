<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\ServiceOrder;
use App\Models\Package;
use App\Models\PackageOrder;
use App\Models\CustomerRequest;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function dashboard()
    {
        // Count statistics
        $totalUsers = User::count();
        $totalAdmins = User::where('is_admin', true)->count();
        
        // Service statistics
        $totalServices = Service::count();
        $activeServices = Service::where('status', true)->count();
        $featuredServices = Service::where('featured', true)->count();
        
        // Package statistics
        $totalPackages = Package::count();
        $activePackages = Package::where('status', true)->count();
        $featuredPackages = Package::where('featured', true)->count();
        
        // Order statistics
        $totalServiceOrders = ServiceOrder::count();
        $pendingServiceOrders = ServiceOrder::where('status', 'pending')->count();
        $completedServiceOrders = ServiceOrder::where('status', 'completed')->count();
        
        $totalPackageOrders = PackageOrder::count();
        $pendingPackageOrders = PackageOrder::where('status', 'pending')->count();
        $completedPackageOrders = PackageOrder::where('status', 'completed')->count();
        
        // Customer requests
        $totalRequests = CustomerRequest::count();
        $pendingRequests = CustomerRequest::where('status', CustomerRequest::STATUS_PENDING)->count();
        $completedRequests = CustomerRequest::where('status', CustomerRequest::STATUS_DONE)->count();
        
        // Revenue statistics
        $totalRevenue = Transaction::sum('amount');
        $monthlyRevenue = Transaction::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('amount');
        
        // Recent service orders
        $recentServiceOrders = ServiceOrder::orderBy('created_at', 'desc')
            ->take(5)
            ->get();
            
        // Recent package orders
        $recentPackageOrders = PackageOrder::orderBy('created_at', 'desc')
            ->take(5)
            ->get();
            
        // Recent customer requests
        $recentRequests = CustomerRequest::orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        
        return view('admin.dashboard', compact(
            'totalUsers', 'totalAdmins',
            'totalServices', 'activeServices', 'featuredServices',
            'totalPackages', 'activePackages', 'featuredPackages',
            'totalServiceOrders', 'pendingServiceOrders', 'completedServiceOrders',
            'totalPackageOrders', 'pendingPackageOrders', 'completedPackageOrders',
            'totalRequests', 'pendingRequests', 'completedRequests',
            'totalRevenue', 'monthlyRevenue',
            'recentServiceOrders', 'recentPackageOrders', 'recentRequests'
        ));
    }
}
