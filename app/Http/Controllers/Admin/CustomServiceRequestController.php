<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomServiceRequest;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomServiceRequestController extends Controller
{
    /**
     * Display a listing of custom service requests.
     */
    public function index(Request $request)
    {
        try {
            $query = CustomServiceRequest::with(['user', 'items', 'assignedTo', 'transaction'])
                ->latest();
            
            // Filter by status if provided
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }
            
            // Filter by service type if provided
            if ($request->filled('service_type')) {
                $query->where('service_type', $request->service_type);
            }
            
            // Search by customer name or email
            if ($request->filled('search')) {
                $search = $request->search;
                $query->whereHas('user', function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            }
            
            // Switch to client-side DataTables pagination by returning full collection
            $customServiceRequests = $query->get();
            
            return view('admin.custom-service-requests.index', compact('customServiceRequests'));
            
        } catch (\Exception $e) {
            \Log::error('Error fetching custom service requests: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Failed to load custom service requests. Please try again.');
        }
    }
    
    /**
     * Display the specified custom service request.
     */
    public function show(CustomServiceRequest $customServiceRequest)
    {
        try {
            $customServiceRequest->load(['user', 'items', 'assignedTo', 'transaction']);
            
            return view('admin.custom-service-requests.show', compact('customServiceRequest'));
            
        } catch (\Exception $e) {
            \Log::error('Error fetching custom service request: ' . $e->getMessage());
            
            return redirect()->route('admin.custom-service-requests.index')
                ->with('error', 'Failed to load custom service request details.');
        }
    }
    
    /**
     * Update the status of a custom service request.
     */
    public function updateStatus(Request $request, CustomServiceRequest $customServiceRequest)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:pending,in_progress,completed,cancelled'],
            'admin_notes' => ['nullable', 'string', 'max:1000'],
            'assigned_to' => ['nullable', 'exists:users,id'],
        ]);
        
        DB::beginTransaction();
        
        try {
            $updateData = [
                'status' => $validated['status'],
                'admin_notes' => $validated['admin_notes'] ?? $customServiceRequest->admin_notes,
            ];
            
            // Set assigned user if provided
            if (isset($validated['assigned_to'])) {
                $updateData['assigned_to'] = $validated['assigned_to'];
            }
            
            // Set timestamps based on status
            if ($validated['status'] === CustomServiceRequest::STATUS_IN_PROGRESS && !$customServiceRequest->started_at) {
                $updateData['started_at'] = now();
            }
            
            if ($validated['status'] === CustomServiceRequest::STATUS_COMPLETED && !$customServiceRequest->completed_at) {
                $updateData['completed_at'] = now();
            }
            
            $customServiceRequest->update($updateData);
            
            DB::commit();
            
            return redirect()->back()
                ->with('success', 'Custom service request status updated successfully.');
                
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Error updating custom service request status: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Failed to update status. Please try again.');
        }
    }
    
    /**
     * Assign a custom service request to an admin.
     */
    public function assign(Request $request, CustomServiceRequest $customServiceRequest)
    {
        $validated = $request->validate([
            'assigned_to' => ['required', 'exists:users,id'],
        ]);
        
        try {
            $customServiceRequest->update([
                'assigned_to' => $validated['assigned_to'],
            ]);
            
            return redirect()->back()
                ->with('success', 'Custom service request assigned successfully.');
                
        } catch (\Exception $e) {
            \Log::error('Error assigning custom service request: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Failed to assign request. Please try again.');
        }
    }
    
    /**
     * Get statistics for the dashboard.
     */
    public function getStats()
    {
        try {
            $stats = [
                'total' => CustomServiceRequest::count(),
                'pending' => CustomServiceRequest::where('status', CustomServiceRequest::STATUS_PENDING)->count(),
                'in_progress' => CustomServiceRequest::where('status', CustomServiceRequest::STATUS_IN_PROGRESS)->count(),
                'completed' => CustomServiceRequest::where('status', CustomServiceRequest::STATUS_COMPLETED)->count(),
                'cancelled' => CustomServiceRequest::where('status', CustomServiceRequest::STATUS_CANCELLED)->count(),
                'marketing' => CustomServiceRequest::where('service_type', CustomServiceRequest::TYPE_MARKETING)->count(),
                'web_app' => CustomServiceRequest::where('service_type', CustomServiceRequest::TYPE_WEB_APP)->count(),
                'total_revenue' => CustomServiceRequest::where('status', CustomServiceRequest::STATUS_COMPLETED)->sum('total_amount'),
            ];
            
            return response()->json($stats);
            
        } catch (\Exception $e) {
            \Log::error('Error fetching custom service stats: ' . $e->getMessage());
            
            return response()->json(['error' => 'Failed to load statistics'], 500);
        }
    }
}
