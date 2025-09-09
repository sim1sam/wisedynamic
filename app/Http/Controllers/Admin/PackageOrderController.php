<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PackageOrder;
use Illuminate\Support\Facades\Log;

class PackageOrderController extends Controller
{
    /**
     * Display a listing of package orders.
     */
    public function index()
    {
        try {
            $orders = PackageOrder::orderBy('created_at', 'desc')->get();
            
            return view('admin.package-orders.index', [
                'orders' => $orders
            ]);
        } catch (\Exception $e) {
            Log::error('Error loading package orders', [
                'error' => $e->getMessage()
            ]);
            
            return redirect()->route('admin.dashboard')
                ->with('error', 'Failed to load package orders: ' . $e->getMessage());
        }
    }
    
    /**
     * Display the specified package order.
     */
    public function show(PackageOrder $packageOrder)
    {
        try {
            return view('admin.package-orders.show', [
                'order' => $packageOrder
            ]);
        } catch (\Exception $e) {
            Log::error('Error viewing package order', [
                'error' => $e->getMessage(),
                'order_id' => $packageOrder->id
            ]);
            
            return redirect()->route('admin.package-orders.index')
                ->with('error', 'Failed to view package order: ' . $e->getMessage());
        }
    }
    
    /**
     * Update the status of the specified package order.
     */
    public function updateStatus(Request $request, PackageOrder $packageOrder)
    {
        try {
            $validated = $request->validate([
                'status' => 'required|in:pending,processing,completed,cancelled',
            ]);
            
            $packageOrder->update([
                'status' => $validated['status']
            ]);
            
            return redirect()->route('admin.package-orders.show', $packageOrder)
                ->with('success', 'Package order status updated successfully.');
        } catch (\Exception $e) {
            Log::error('Error updating package order status', [
                'error' => $e->getMessage(),
                'order_id' => $packageOrder->id
            ]);
            
            return redirect()->route('admin.package-orders.show', $packageOrder)
                ->with('error', 'Failed to update package order status: ' . $e->getMessage());
        }
    }
}
