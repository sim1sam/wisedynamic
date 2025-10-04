<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FundRequest;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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
        
        // Switch to client-side DataTables pagination by returning full collection
        $fundRequests = $query->get();
        
        return view('admin.fund-requests.index', compact('fundRequests'));
    }
    
    /**
     * Show the form for creating a new fund request.
     */
    public function create()
    {
        $users = User::where('is_admin', false)->orderBy('name')->get(['id', 'name', 'email']);
        return view('admin.fund-requests.create', compact('users'));
    }

    /**
     * Store a newly created fund request.
     */
    public function store(Request $request)
    {
        // Debug: Log the incoming request data
        Log::info('Fund request form submitted', $request->all());
        
        try {
            $validated = $request->validate([
                'user_id' => ['required', 'exists:users,id'],
                'amount' => ['required', 'numeric', 'min:1', 'max:100000'],
                'service_info' => ['nullable', 'string', 'max:1000'],
                'payment_method' => ['required', 'in:ssl,manual'],
                'bank_name' => ['nullable', 'required_if:payment_method,manual', 'string', 'max:255'],
                'account_number' => ['nullable', 'required_if:payment_method,manual', 'string', 'max:255'],
                'admin_notes' => ['nullable', 'string', 'max:1000'],
                'auto_approve' => ['nullable', 'boolean'],
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Fund request validation failed', [
                'errors' => $e->validator->errors()->toArray(),
                'input' => $request->all()
            ]);
            return redirect()->back()->withErrors($e->validator)->withInput();
        }

        try {
            $fundRequest = FundRequest::create([
                'user_id' => $validated['user_id'],
                'amount' => $validated['amount'],
                'service_info' => $validated['service_info'],
                'payment_method' => $validated['payment_method'],
                'bank_name' => $validated['bank_name'] ?? null,
                'account_number' => $validated['account_number'] ?? null,
                'admin_notes' => $validated['admin_notes'],
                'status' => $request->boolean('auto_approve') ? FundRequest::STATUS_APPROVED : FundRequest::STATUS_PENDING,
                'approved_at' => $request->boolean('auto_approve') ? now() : null,
                'approved_by' => $request->boolean('auto_approve') ? Auth::id() : null,
            ]);

            // If auto-approved, add balance to user and create transaction
            if ($request->boolean('auto_approve')) {
                $user = User::find($validated['user_id']);
                $user->addBalance($validated['amount']);

                Transaction::create([
                    'transaction_number' => Transaction::generateTransactionNumber(),
                    'fund_request_id' => $fundRequest->id,
                    'amount' => $validated['amount'],
                    'payment_method' => $validated['payment_method'] === 'ssl' ? 'SSL Payment' : 'Bank Transfer',
                    'status' => 'completed',
                    'notes' => 'Fund request created and auto-approved by admin. ' . ($validated['admin_notes'] ? 'Admin notes: ' . $validated['admin_notes'] : ''),
                ]);
            }

            // Create notification for the user
            \App\Models\Notification::createNotification(
                'fund_request',
                'Fund Request Created',
                "A fund request for ৳{$validated['amount']} has been " . ($request->boolean('auto_approve') ? 'created and approved' : 'created') . " by admin.",
                route('customer.fund.show', $fundRequest->id),
                $fundRequest->id,
                'App\\Models\\FundRequest'
            );

            $message = $request->boolean('auto_approve') 
                ? 'Fund request created and approved successfully. User balance updated.'
                : 'Fund request created successfully.';

            return redirect()->route('admin.fund-requests.index')->with('success', $message);
            
        } catch (\Exception $e) {
            Log::error('Fund request creation failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to create fund request: ' . $e->getMessage())->withInput();
        }
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
        
        // Create transaction record
        Transaction::create([
            'transaction_number' => Transaction::generateTransactionNumber(),
            'fund_request_id' => $fundRequest->id,
            'amount' => $fundRequest->amount,
            'payment_method' => $fundRequest->payment_method === 'ssl' ? 'SSL Payment' : 'Bank Transfer',
            'status' => 'completed',
            'notes' => 'Fund request approved - Balance added to customer account. ' . ($request->admin_notes ? 'Admin notes: ' . $request->admin_notes : ''),
        ]);
        
        // Create notification for the user
        \App\Models\Notification::createNotification(
            'fund_approved',
            'Fund Request Approved',
            "Your fund request for ৳{$fundRequest->amount} has been approved and added to your balance.",
            route('customer.fund.show', $fundRequest->id),
            $fundRequest->id,
            'App\\Models\\FundRequest'
        );
        
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
        
        // Create notification for the user
        \App\Models\Notification::createNotification(
            'fund_rejected',
            'Fund Request Rejected',
            "Your fund request for ৳{$fundRequest->amount} has been rejected. Reason: {$request->admin_notes}",
            route('customer.fund.show', $fundRequest->id),
            $fundRequest->id,
            'App\\Models\\FundRequest'
        );
        
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
