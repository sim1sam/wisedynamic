<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\FundRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FundController extends Controller
{
    /**
     * Display fund request form and history.
     */
    public function index()
    {
        $user = Auth::user();
        $fundRequests = $user->fundRequests()->latest()->paginate(10);
        
        return view('frontend.customer.fund.index', compact('fundRequests'));
    }
    
    /**
     * Show the form for creating a new fund request.
     */
    public function create()
    {
        return view('frontend.customer.fund.create');
    }
    
    /**
     * Store a newly created fund request.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'amount' => ['required', 'numeric', 'min:1', 'max:100000'],
            'service_info' => ['nullable', 'string', 'max:1000'],
            'payment_method' => ['required', 'in:ssl,manual'],
            'bank_name' => ['required_if:payment_method,manual', 'string', 'max:255'],
            'account_number' => ['required_if:payment_method,manual', 'string', 'max:255'],
            'payment_screenshot' => ['required_if:payment_method,manual', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);
        
        $fundRequest = new FundRequest([
            'user_id' => Auth::id(),
            'amount' => $validated['amount'],
            'service_info' => $validated['service_info'],
            'payment_method' => $validated['payment_method'],
        ]);
        
        if ($validated['payment_method'] === 'manual') {
            // Handle manual payment
            $fundRequest->bank_name = $validated['bank_name'];
            $fundRequest->account_number = $validated['account_number'];
            
            // Store payment screenshot
            if ($request->hasFile('payment_screenshot')) {
                $path = $request->file('payment_screenshot')->store('fund-screenshots', 'public');
                $fundRequest->payment_screenshot = $path;
            }
            
            $fundRequest->save();
            
            return redirect()->route('customer.fund.index')
                ->with('success', 'Fund request submitted successfully. Please wait for admin approval.');
        } else {
            // Handle SSL payment - redirect to payment gateway
            $fundRequest->save();
            
            return redirect()->route('customer.fund.ssl-payment', $fundRequest)
                ->with('info', 'Redirecting to payment gateway...');
        }
    }
    
    /**
     * Show SSL payment page.
     */
    public function sslPayment(FundRequest $fundRequest)
    {
        // Ensure the fund request belongs to the authenticated user
        if ($fundRequest->user_id !== Auth::id()) {
            abort(403);
        }
        
        // For now, we'll show a placeholder SSL payment page
        // In production, this would integrate with actual SSL payment gateway
        return view('frontend.customer.fund.ssl-payment', compact('fundRequest'));
    }
    
    /**
     * Handle SSL payment success callback.
     */
    public function sslSuccess(Request $request, FundRequest $fundRequest)
    {
        // Ensure the fund request belongs to the authenticated user
        if ($fundRequest->user_id !== Auth::id()) {
            abort(403);
        }
        
        // In production, verify the SSL payment response
        // For now, we'll simulate a successful payment
        $fundRequest->update([
            'ssl_transaction_id' => $request->get('transaction_id', 'SSL_' . time()),
            'ssl_response' => $request->all(),
            'status' => FundRequest::STATUS_APPROVED,
            'approved_at' => now(),
        ]);
        
        // Add balance to user account
        Auth::user()->addBalance($fundRequest->amount);
        
        return redirect()->route('customer.fund.index')
            ->with('success', 'Payment successful! Your account has been credited with BDT ' . number_format($fundRequest->amount));
    }
    
    /**
     * Handle SSL payment failure callback.
     */
    public function sslFail(Request $request, FundRequest $fundRequest)
    {
        // Ensure the fund request belongs to the authenticated user
        if ($fundRequest->user_id !== Auth::id()) {
            abort(403);
        }
        
        $fundRequest->update([
            'status' => FundRequest::STATUS_REJECTED,
            'admin_notes' => 'SSL payment failed: ' . $request->get('error', 'Unknown error'),
        ]);
        
        return redirect()->route('customer.fund.index')
            ->with('error', 'Payment failed. Please try again or contact support.');
    }
    
    /**
     * Show the specified fund request.
     */
    public function show(FundRequest $fundRequest)
    {
        // Ensure the fund request belongs to the authenticated user
        if ($fundRequest->user_id !== Auth::id()) {
            abort(403);
        }
        
        return view('frontend.customer.fund.show', compact('fundRequest'));
    }
}
