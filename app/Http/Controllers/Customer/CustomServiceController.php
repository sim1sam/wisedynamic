<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\CustomServiceRequest;
use App\Models\CustomServiceItem;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CustomServiceController extends Controller
{
    /**
     * Display a listing of custom service requests.
     */
    public function index()
    {
        $user = Auth::user();
        $customServiceRequests = $user->customServiceRequests()
            ->with('items')
            ->latest()
            ->paginate(10);
        
        return view('frontend.customer.custom-service.index', compact('customServiceRequests'));
    }
    
    /**
     * Show the form for creating a new custom service request.
     */
    public function create()
    {
        return view('frontend.customer.custom-service.create');
    }
    
    /**
     * Store a newly created custom service request.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'service_type' => ['required', 'in:marketing,web_app'],
            'payment_method' => ['required', 'in:balance,ssl'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.service_name' => ['required', 'string', 'max:255'],
            'items.*.amount' => ['required', 'numeric', 'min:1'],
            // Marketing fields
             'items.*.platform' => ['nullable', 'string', 'max:255'],
             'items.*.post_link' => ['nullable', 'url'],
             'items.*.service_date' => ['nullable', 'date', 'after_or_equal:today'],
             'items.*.duration_days' => ['nullable', 'integer', 'min:1', 'max:365'],
            // Web/App fields
            'items.*.domain_name' => ['nullable', 'string', 'max:255'],
            'items.*.duration_months' => ['nullable', 'integer', 'min:1', 'max:60'],
            // Common fields
            'items.*.description' => ['nullable', 'string', 'max:1000'],
        ]);
        
        // Calculate total amount
        $totalAmount = collect($validated['items'])->sum('amount');
        
        // Check if user has sufficient balance for balance payment
        if ($validated['payment_method'] === 'balance') {
            if (Auth::user()->balance < $totalAmount) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Insufficient balance. Your current balance is BDT ' . number_format(Auth::user()->balance, 2) . '. Please add funds to your account.');
            }
        }
        
        DB::beginTransaction();
        
        try {
            // Create the custom service request
            $customServiceRequest = CustomServiceRequest::create([
                'user_id' => Auth::id(),
                'service_type' => $validated['service_type'],
                'total_amount' => $totalAmount,
                'payment_method' => $validated['payment_method'],
                'status' => CustomServiceRequest::STATUS_PENDING,
            ]);
            
            // Create service items
            foreach ($validated['items'] as $itemData) {
                CustomServiceItem::create([
                     'custom_service_request_id' => $customServiceRequest->id,
                     'service_name' => $itemData['service_name'],
                     'amount' => $itemData['amount'],
                     'platform' => $itemData['platform'] ?? null,
                     'post_link' => $itemData['post_link'] ?? null,
                     'service_date' => $itemData['service_date'] ?? null,
                     'duration_days' => $itemData['duration_days'] ?? null,
                     'domain_name' => $itemData['domain_name'] ?? null,
                     'duration_months' => $itemData['duration_months'] ?? null,
                     'description' => $itemData['description'] ?? null,
                 ]);
            }
            
            // Create notification for admin
            \App\Models\Notification::createNotification(
                'custom_service',
                'New Custom Service Request',
                "New custom service request #{$customServiceRequest->id} from {$customServiceRequest->user->name} for BDT {$totalAmount}",
                route('admin.custom-service-requests.show', $customServiceRequest->id),
                $customServiceRequest->id,
                'App\\Models\\CustomServiceRequest'
            );
            
            if ($validated['payment_method'] === 'balance') {
                // Deduct from user balance
                Auth::user()->deductBalance($totalAmount);
                
                // Create transaction record
                Transaction::create([
                    'transaction_number' => Transaction::generateTransactionNumber(),
                    'custom_service_request_id' => $customServiceRequest->id,
                    'amount' => $totalAmount,
                    'payment_method' => 'Balance Payment',
                    'status' => 'completed',
                    'notes' => 'Custom service request payment - Balance deducted from customer account.',
                ]);
                
                DB::commit();
                
                return redirect()->route('customer.custom-service.show', $customServiceRequest)
                    ->with('success', 'Custom service request submitted successfully! Payment completed using account balance.');
            } else {
                // SSL payment - redirect to payment gateway
                DB::commit();
                
                return redirect()->route('customer.custom-service.ssl-payment', $customServiceRequest)
                    ->with('info', 'Redirecting to payment gateway...');
            }
            
        } catch (\Exception $e) {
            DB::rollback();
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create custom service request. Please try again.');
        }
    }
    
    /**
     * Display the specified custom service request.
     */
    public function show(CustomServiceRequest $customServiceRequest)
    {
        // Ensure the request belongs to the authenticated user
        if ($customServiceRequest->user_id !== Auth::id()) {
            abort(403);
        }
        
        $customServiceRequest->load(['items', 'transaction']);
        
        return view('frontend.customer.custom-service.show', compact('customServiceRequest'));
    }
    
    /**
     * Show SSL payment page.
     */
    public function sslPayment(CustomServiceRequest $customServiceRequest)
    {
        // Ensure the request belongs to the authenticated user
        if ($customServiceRequest->user_id !== Auth::id()) {
            abort(403);
        }
        
        // For now, we'll show a placeholder SSL payment page
        return view('frontend.customer.custom-service.ssl-payment', compact('customServiceRequest'));
    }
    
    /**
     * Handle SSL payment success callback.
     */
    public function sslSuccess(Request $request, CustomServiceRequest $customServiceRequest)
    {
        // Ensure the request belongs to the authenticated user
        if ($customServiceRequest->user_id !== Auth::id()) {
            abort(403);
        }
        
        // In production, verify the SSL payment response
        $customServiceRequest->update([
            'ssl_transaction_id' => $request->get('transaction_id', 'SSL_' . time()),
            'ssl_response' => $request->all(),
        ]);
        
        // Create transaction record
        Transaction::create([
            'transaction_number' => Transaction::generateTransactionNumber(),
            'custom_service_request_id' => $customServiceRequest->id,
            'amount' => $customServiceRequest->total_amount,
            'payment_method' => 'SSL Payment',
            'status' => 'completed',
            'notes' => 'Custom service request SSL payment successful. Transaction ID: ' . $customServiceRequest->ssl_transaction_id,
        ]);
        
        return redirect()->route('customer.custom-service.show', $customServiceRequest)
            ->with('success', 'Payment successful! Your custom service request has been submitted.');
    }
    
    /**
     * Handle SSL payment failure callback.
     */
    public function sslFail(Request $request, CustomServiceRequest $customServiceRequest)
    {
        // Ensure the request belongs to the authenticated user
        if ($customServiceRequest->user_id !== Auth::id()) {
            abort(403);
        }
        
        $customServiceRequest->update([
            'status' => CustomServiceRequest::STATUS_CANCELLED,
            'admin_notes' => 'SSL payment failed: ' . $request->get('error', 'Unknown error'),
        ]);
        
        return redirect()->route('customer.custom-service.index')
            ->with('error', 'Payment failed. Please try again or contact support.');
    }
}
