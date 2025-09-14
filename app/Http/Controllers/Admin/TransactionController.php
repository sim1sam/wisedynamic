<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\PackageOrder;
use App\Models\ServiceOrder;
use Illuminate\Support\Facades\Log;

class TransactionController extends Controller
{
    /**
     * Display a listing of all transactions.
     */
    public function index()
    {
        try {
            // Get all transactions with their related orders and fund requests
            $transactions = Transaction::with(['packageOrder', 'serviceOrder', 'fundRequest.user'])
                ->orderBy('created_at', 'desc')
                ->get();
            
            return view('admin.transactions.index', [
                'transactions' => $transactions
            ]);
        } catch (\Exception $e) {
            Log::error('Error loading transactions', [
                'error' => $e->getMessage()
            ]);
            
            return redirect()->route('admin.dashboard')
                ->with('error', 'Failed to load transactions: ' . $e->getMessage());
        }
    }
    
    /**
     * Display the specified transaction.
     */
    public function show(Transaction $transaction)
    {
        try {
            // Load related orders and fund requests
            $transaction->load(['packageOrder', 'serviceOrder', 'fundRequest.user']);
            
            return view('admin.transactions.show', [
                'transaction' => $transaction
            ]);
        } catch (\Exception $e) {
            Log::error('Error viewing transaction', [
                'error' => $e->getMessage(),
                'transaction_id' => $transaction->id
            ]);
            
            return redirect()->route('admin.transactions.index')
                ->with('error', 'Failed to view transaction: ' . $e->getMessage());
        }
    }
}
