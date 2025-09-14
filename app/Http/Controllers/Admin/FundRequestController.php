<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FundRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FundRequestController extends Controller
{
    /**
     * Display a listing of fund requests.
     */
    public function index(Request $request)
    {
        $query = FundRequest::with(['user', 'approvedBy'])->latest();
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Filter by payment method
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }
        
        $fundRequests = $query->paginate(20);
        
        return view('admin.fund-requests.index', compact('fundRequests'));
    }
    
    /**
     * Display the specified fund request.
     */
    public function show(FundRequest $fundRequest)
    {
        $fundRequest->load(['user', 'approvedBy']);
        
        return view('admin.fund-requests.show', compact('fundRequest'));
    }
    
    /**
     * Approve a fund request.
     */
    public function approve(Request $request, FundRequest $fundRequest)
    {
        $request->validate([
            'admin_notes' => ['nullable', 'string', 'max:1000'],
        ]);
        
        if ($fundRequest->status !== FundRequest::STATUS_PENDING) {
            return redirect()->back()
                ->with('error', 'Only pending requests can be approved.');
        }
        
        $fundRequest->update([
            'status' => FundRequest::STATUS_APPROVED,
            'admin_notes' => $request->admin_notes,
            'approved_at' => now(),
            'approved_by' => Auth::id(),
        ]);
        
        // Add balance to user account
        $fundRequest->user->addBalance($fundRequest->amount);
        
        return redirect()->route('admin.fund-requests.index')
            ->with('success', 'Fund request approved successfully. User balance updated.');
    }
    
    /**
     * Reject a fund request.
     */
    public function reject(Request $request, FundRequest $fundRequest)
    {
        $request->validate([
            'admin_notes' => ['required', 'string', 'max:1000'],
        ]);
        
        if ($fundRequest->status !== FundRequest::STATUS_PENDING) {
            return redirect()->back()
                ->with('error', 'Only pending requests can be rejected.');
        }
        
        $fundRequest->update([
            'status' => FundRequest::STATUS_REJECTED,
            'admin_notes' => $request->admin_notes,
            'approved_by' => Auth::id(),
        ]);
        
        return redirect()->route('admin.fund-requests.index')
            ->with('success', 'Fund request rejected successfully.');
    }
    
    /**
     * Show the form for editing the specified fund request.
     */
    public function edit(FundRequest $fundRequest)
    {
        return view('admin.fund-requests.edit', compact('fundRequest'));
    }
    
    /**
     * Update the specified fund request.
     */
    public function update(Request $request, FundRequest $fundRequest)
    {
        $validated = $request->validate([
            'amount' => ['required', 'numeric', 'min:1', 'max:100000'],
            'service_info' => ['nullable', 'string', 'max:1000'],
            'admin_notes' => ['nullable', 'string', 'max:1000'],
        ]);
        
        $fundRequest->update($validated);
        
        return redirect()->route('admin.fund-requests.show', $fundRequest)
            ->with('success', 'Fund request updated successfully.');
    }
    
    /**
     * Get statistics for fund requests.
     */
    public function statistics()
    {
        $stats = [
            'total_requests' => FundRequest::count(),
            'pending_requests' => FundRequest::where('status', FundRequest::STATUS_PENDING)->count(),
            'approved_requests' => FundRequest::where('status', FundRequest::STATUS_APPROVED)->count(),
            'rejected_requests' => FundRequest::where('status', FundRequest::STATUS_REJECTED)->count(),
            'total_amount_requested' => FundRequest::sum('amount'),
            'total_amount_approved' => FundRequest::where('status', FundRequest::STATUS_APPROVED)->sum('amount'),
            'ssl_payments' => FundRequest::where('payment_method', FundRequest::PAYMENT_SSL)->count(),
            'manual_payments' => FundRequest::where('payment_method', FundRequest::PAYMENT_MANUAL)->count(),
        ];
        
        return response()->json($stats);
    }
}
