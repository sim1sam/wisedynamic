<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentAuditLog;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentAuditController extends Controller
{
    /**
     * Display a listing of payment audit logs.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = PaymentAuditLog::query()
            ->with('user')
            ->orderBy('created_at', 'desc');
        
        // Filter by action
        if ($request->has('action') && $request->action) {
            $query->where('action', $request->action);
        }
        
        // Filter by transaction ID
        if ($request->has('transaction_id') && $request->transaction_id) {
            $query->where('transaction_id', 'like', '%' . $request->transaction_id . '%');
        }
        
        // Filter by user
        if ($request->has('user_id') && $request->user_id) {
            $query->where('user_id', $request->user_id);
        }
        
        // Filter by date range
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        $logs = $query->paginate(20);
        
        // Get transactions for the logs
        $transactionIds = $logs->pluck('transaction_id')->filter()->unique()->toArray();
        $transactions = [];
        
        if (!empty($transactionIds)) {
            $transactionRecords = Transaction::whereIn('ssl_transaction_id', $transactionIds)
                ->orWhereIn('id', $transactionIds)
                ->get();
                
            foreach ($transactionRecords as $transaction) {
                $transactions[$transaction->id] = $transaction;
                if ($transaction->ssl_transaction_id) {
                    $transactions[$transaction->ssl_transaction_id] = $transaction;
                }
            }
        }
        
        // Get users for filter dropdown
        $users = User::orderBy('name')->get(['id', 'name', 'email']);
        
        // Get available actions for filter dropdown
        $actions = PaymentAuditLog::select('action')
            ->distinct()
            ->pluck('action');
        
        return view('admin.payment-audit.index', compact(
            'logs', 
            'transactions', 
            'users', 
            'actions'
        ));
    }
    
    /**
     * Display the specified payment audit log.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $log = PaymentAuditLog::with('user')->findOrFail($id);
        
        // Get transaction if available
        $transaction = null;
        if ($log->transaction_id) {
            $transaction = Transaction::where('ssl_transaction_id', $log->transaction_id)
                ->orWhere('id', $log->transaction_id)
                ->first();
        }
        
        return view('admin.payment-audit.show', compact('log', 'transaction'));
    }
    
    /**
     * Get customer information for a transaction.
     *
     * @param  string  $transactionId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCustomerInfo($transactionId)
    {
        $transaction = Transaction::where('ssl_transaction_id', $transactionId)
            ->orWhere('id', $transactionId)
            ->first();
            
        if (!$transaction) {
            return response()->json([
                'success' => false,
                'message' => 'Transaction not found'
            ]);
        }
        
        $customerInfo = [
            'name' => $transaction->customer_name,
            'email' => $transaction->customer_email,
            'phone' => $transaction->customer_phone,
            'address' => $transaction->customer_address,
            'is_registered' => false
        ];
        
        // Check if this is a registered customer
        if ($transaction->customer_email) {
            $user = User::where('email', $transaction->customer_email)->first();
            if ($user) {
                $customerInfo['is_registered'] = true;
                $customerInfo['user_id'] = $user->id;
            }
        }
        
        return response()->json([
            'success' => true,
            'customer' => $customerInfo
        ]);
    }
    
    /**
     * Get payment statistics.
     *
     * @return \Illuminate\Http\Response
     */
    public function statistics()
    {
        // Get payment statistics by day for the last 30 days
        $dailyStats = DB::table('payment_audit_logs')
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'), 'action')
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date', 'action')
            ->orderBy('date')
            ->get();
            
        // Format data for chart
        $dates = $dailyStats->pluck('date')->unique()->values();
        $actions = $dailyStats->pluck('action')->unique()->values();
        
        $chartData = [
            'labels' => $dates->toArray(),
            'datasets' => []
        ];
        
        $colors = [
            'payment_attempt' => '#3490dc',
            'payment_success' => '#38c172',
            'payment_failure' => '#e3342f'
        ];
        
        foreach ($actions as $action) {
            $data = [];
            
            foreach ($dates as $date) {
                $count = $dailyStats->where('date', $date)->where('action', $action)->first()->count ?? 0;
                $data[] = $count;
            }
            
            $chartData['datasets'][] = [
                'label' => ucwords(str_replace('_', ' ', $action)),
                'data' => $data,
                'backgroundColor' => $colors[$action] ?? '#6c757d',
                'borderColor' => $colors[$action] ?? '#6c757d',
            ];
        }
        
        // Get total counts
        $totalAttempts = PaymentAuditLog::where('action', 'payment_attempt')->count();
        $totalSuccesses = PaymentAuditLog::where('action', 'payment_success')->count();
        $totalFailures = PaymentAuditLog::where('action', 'payment_failure')->count();
        
        return view('admin.payment-audit.statistics', compact(
            'chartData',
            'totalAttempts',
            'totalSuccesses',
            'totalFailures'
        ));
    }
}
