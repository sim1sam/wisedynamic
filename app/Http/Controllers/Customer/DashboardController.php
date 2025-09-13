<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PackageOrder;
use App\Models\ServiceOrder;
use App\Models\CustomerRequest;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    /**
     * Display the customer dashboard.
     */
    public function index()
    {
        try {
            $user = Auth::user();
            
            // Get customer's statistics
            $packageOrders = PackageOrder::where('email', $user->email)->get();
            $serviceOrders = ServiceOrder::where('email', $user->email)->get();
            $customerRequests = CustomerRequest::where('user_id', $user->id)->get();
            
            // Calculate statistics
            $stats = [
                'total_package_orders' => $packageOrders->count(),
                'total_service_orders' => $serviceOrders->count(),
                'total_requests' => $customerRequests->count(),
                'pending_orders' => $packageOrders->where('status', 'pending')->count() + $serviceOrders->where('status', 'pending')->count(),
                'completed_orders' => $packageOrders->where('status', 'completed')->count() + $serviceOrders->where('status', 'completed')->count(),
                'pending_requests' => $customerRequests->where('status', 'pending')->count(),
                'total_spent' => $packageOrders->sum('amount') + $serviceOrders->sum('amount'),
            ];
            
            // Get recent activities
            $recentPackageOrders = $packageOrders->sortByDesc('created_at')->take(3);
            $recentServiceOrders = $serviceOrders->sortByDesc('created_at')->take(3);
            $recentRequests = $customerRequests->sortByDesc('created_at')->take(3);
            
            return view('frontend.customer.index', compact(
                'stats',
                'recentPackageOrders',
                'recentServiceOrders', 
                'recentRequests'
            ));
        } catch (\Exception $e) {
            Log::error('Error loading customer dashboard', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id()
            ]);
            
            return view('frontend.customer.index', [
                'stats' => [
                    'total_package_orders' => 0,
                    'total_service_orders' => 0,
                    'total_requests' => 0,
                    'pending_orders' => 0,
                    'completed_orders' => 0,
                    'pending_requests' => 0,
                    'total_spent' => 0,
                ],
                'recentPackageOrders' => collect([]),
                'recentServiceOrders' => collect([]),
                'recentRequests' => collect([])
            ]);
        }
    }
}