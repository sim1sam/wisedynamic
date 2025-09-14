@extends('layouts.customer')

@section('content')
<div class="bg-gray-100 py-12">
    <div class="container mx-auto px-4">
        <div class="flex flex-wrap items-center justify-between mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Order #{{ $order->id }}</h1>
            <a href="{{ route('customer.orders.index') }}" class="btn-outline-primary px-4 py-2 rounded-lg">
                <i class="fas fa-arrow-left mr-2"></i> Back to Orders
            </a>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                <p>{{ session('success') }}</p>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                <p>{{ session('error') }}</p>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Order Summary -->
            <div class="md:col-span-2">
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                        <h2 class="text-xl font-semibold text-gray-800">Order Summary</h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Package Details -->
                            <div>
                                <h3 class="text-lg font-semibold text-gray-700 mb-3">Package Details</h3>
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <p class="text-gray-700 font-medium">Package:</p>
                                    <p class="text-gray-900 mb-2">{{ $order->package_name }}</p>
                                    
                                    <p class="text-gray-700 font-medium">Amount:</p>
                                    <p class="text-gray-900 text-2xl font-bold mb-2">BDT {{ number_format($order->amount) }}</p>
                                    
                                    <p class="text-gray-700 font-medium">Order Date:</p>
                                    <p class="text-gray-900 mb-2">{{ $order->created_at->format('M d, Y h:i A') }}</p>
                                    
                                    <p class="text-gray-700 font-medium">Status:</p>
                                    <p class="mb-2">
                                        @if($order->status === 'pending')
                                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                Pending
                                            </span>
                                        @elseif($order->status === 'processing')
                                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                Processing
                                            </span>
                                        @elseif($order->status === 'completed')
                                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Completed
                                            </span>
                                        @elseif($order->status === 'accepted')
                                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Accepted
                                            </span>
                                        @elseif($order->status === 'cancelled')
                                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                Cancelled
                                            </span>
                                        @endif
                                    </p>
                                    
                                    @if($order->status === 'accepted' && ($order->payment_status ?? 'unpaid') !== 'paid')
                                        <div class="mt-4">
                                            <a href="{{ route('customer.payment.options', ['package', $order->id]) }}" class="w-full inline-block text-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-semibold">
                                                <i class="fas fa-credit-card mr-2"></i>Pay Now
                                            </a>
                                        </div>
                                    @endif
                                    
                                    <div class="mt-4">
                                        <p class="text-gray-700 font-medium">Payment Status:</p>
                                        <div class="bg-gray-50 p-3 rounded-lg mb-3">
                                            <div class="flex justify-between mb-1">
                                                <span>Total Amount:</span>
                                                <span class="font-bold">BDT {{ number_format($order->amount) }}</span>
                                            </div>
                                            <div class="flex justify-between mb-1">
                                                <span>Paid Amount:</span>
                                                <span class="font-bold">BDT {{ number_format($order->paid_amount ?? 0) }}</span>
                                            </div>
                                            <div class="flex justify-between mb-1">
                                                <span>Due Amount:</span>
                                                <span class="font-bold">BDT {{ number_format($order->due_amount ?? $order->amount) }}</span>
                                            </div>
                                            <div class="flex justify-between mb-1">
                                                <span>Payment Status:</span>
                                                <span>
                                                    @if(($order->paid_amount ?? 0) <= 0)
                                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                            Not Paid
                                                        </span>
                                                    @elseif(($order->due_amount ?? $order->amount) <= 0)
                                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                            Fully Paid
                                                        </span>
                                                    @else
                                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                            Partially Paid
                                                        </span>
                                                    @endif
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    @if($order->status === 'processing')
                                        <div class="mt-4">
                                            <form action="{{ route('customer.orders.process-payment', $order) }}" method="POST">
                                                @csrf
                                                <div class="mb-3">
                                                    <label for="payment_amount" class="block text-sm font-medium text-gray-700 mb-1">Payment Amount (BDT)</label>
                                                    <input type="number" id="payment_amount" name="payment_amount" 
                                                        class="w-full border rounded-lg px-4 py-2" 
                                                        value="{{ $order->due_amount }}" 
                                                        min="1" max="{{ $order->due_amount }}" required>
                                                    <p class="text-sm text-gray-500 mt-1">You can pay any amount up to the full due amount</p>
                                                </div>
                                                
                                                <div class="mb-3">
                                                    <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-1">Payment Method</label>
                                                    <select id="payment_method" name="payment_method" class="w-full border rounded-lg px-4 py-2">
                                                        <option value="bank_transfer">Bank Transfer</option>
                                                        <option value="card">Credit/Debit Card</option>
                                                        <option value="mobile_banking">Mobile Banking</option>
                                                    </select>
                                                </div>
                                                
                                                <button type="submit" class="btn-primary w-full py-2 rounded-lg">
                                                    <i class="fas fa-credit-card mr-2"></i> Make Payment
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Customer Information -->
                            <div>
                                <h3 class="text-lg font-semibold text-gray-700 mb-3">Customer Information</h3>
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <p class="text-gray-700 font-medium">Name:</p>
                                    <p class="text-gray-900 mb-2">{{ $order->full_name }}</p>
                                    
                                    <p class="text-gray-700 font-medium">Email:</p>
                                    <p class="text-gray-900 mb-2">{{ $order->email }}</p>
                                    
                                    <p class="text-gray-700 font-medium">Phone:</p>
                                    <p class="text-gray-900 mb-2">{{ $order->phone }}</p>
                                    
                                    @if($order->company)
                                        <p class="text-gray-700 font-medium">Company:</p>
                                        <p class="text-gray-900 mb-2">{{ $order->company }}</p>
                                    @endif
                                    
                                    <p class="text-gray-700 font-medium">Billing Address:</p>
                                    <p class="text-gray-900">
                                        {{ $order->address_line1 }}<br>
                                        @if($order->address_line2)
                                            {{ $order->address_line2 }}<br>
                                        @endif
                                        {{ $order->city }}, {{ $order->state ?? '' }}<br>
                                        {{ $order->postal_code }}, {{ $order->country }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Project Details -->
                        <div class="mt-6">
                            <h3 class="text-lg font-semibold text-gray-700 mb-3">Project Details</h3>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @if($order->website_name)
                                        <div>
                                            <p class="text-gray-700 font-medium">Website/Business Name:</p>
                                            <p class="text-gray-900 mb-2">{{ $order->website_name }}</p>
                                        </div>
                                    @endif
                                    
                                    @if($order->website_type)
                                        <div>
                                            <p class="text-gray-700 font-medium">Website Type:</p>
                                            <p class="text-gray-900 mb-2">{{ $order->website_type }}</p>
                                        </div>
                                    @endif
                                    
                                    @if($order->page_count)
                                        <div>
                                            <p class="text-gray-700 font-medium">Page Count:</p>
                                            <p class="text-gray-900 mb-2">{{ $order->page_count }}</p>
                                        </div>
                                    @endif
                                    
                                    
                                </div>
                                
                                <!-- Transaction History -->
                                <div class="mt-6">
                                    <h3 class="text-lg font-semibold text-gray-700 mb-3">Transaction History</h3>
                                    @if(count($transactions) > 0)
                                        <div class="overflow-x-auto bg-white rounded-lg shadow">
                                            <table class="min-w-full divide-y divide-gray-200">
                                                <thead class="bg-gray-50">
                                                    <tr>
                                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Transaction #</th>
                                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Method</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="bg-white divide-y divide-gray-200">
                                                    @foreach($transactions as $transaction)
                                                        <tr>
                                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $transaction->transaction_number }}</td>
                                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $transaction->created_at->format('M d, Y') }}</td>
                                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">BDT {{ number_format($transaction->amount) }}</td>
                                                            <td class="px-6 py-4 whitespace-nowrap">
                                                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                                    {{ ucfirst($transaction->payment_method) }}
                                                                </span>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                                <tfoot class="bg-gray-50">
                                                    <tr>
                                                        <td colspan="2" class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 text-right">Total Paid:</td>
                                                        <td colspan="2" class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">BDT {{ number_format($order->paid_amount ?? 0) }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2" class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 text-right">Remaining:</td>
                                                        <td colspan="2" class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">BDT {{ number_format($order->due_amount ?? $order->amount) }}</td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    @else
                                        <p class="text-gray-500">No transactions have been recorded yet.</p>
                                    @endif
                                </div>
                                
                                @if($order->notes)
                                    <div class="mt-4">
                                        <p class="text-gray-700 font-medium">Notes:</p>
                                        <p class="text-gray-900 whitespace-pre-line">{{ $order->notes }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Order Timeline -->
            <div>
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                        <h2 class="text-xl font-semibold text-gray-800">Order Timeline</h2>
                    </div>
                    <div class="p-6">
                        <div class="relative border-l-2 border-gray-200 ml-3">
                            <!-- Order Placed -->
                            <div class="mb-8 flex items-center">
                                <div class="absolute -left-3.5">
                                    <div class="h-7 w-7 rounded-full bg-blue-500 flex items-center justify-center">
                                        <i class="fas fa-shopping-cart text-white text-xs"></i>
                                    </div>
                                </div>
                                <div class="ml-6">
                                    <h3 class="text-sm font-semibold text-gray-900">Order Placed</h3>
                                    <p class="text-xs text-gray-500">{{ $order->created_at->format('M d, Y h:i A') }}</p>
                                    <p class="mt-1 text-sm text-gray-700">Your order has been placed successfully.</p>
                                </div>
                            </div>
                            
                            <!-- Order Processing -->
                            @if($order->status !== 'pending' && $order->status !== 'cancelled')
                                <div class="mb-8 flex items-center">
                                    <div class="absolute -left-3.5">
                                        <div class="h-7 w-7 rounded-full bg-yellow-500 flex items-center justify-center">
                                            <i class="fas fa-cog text-white text-xs"></i>
                                        </div>
                                    </div>
                                    <div class="ml-6">
                                        <h3 class="text-sm font-semibold text-gray-900">Order Accepted</h3>
                                        <p class="text-xs text-gray-500">{{ $order->updated_at->format('M d, Y h:i A') }}</p>
                                        <p class="mt-1 text-sm text-gray-700">Your order has been accepted and is being processed.</p>
                                    </div>
                                </div>
                            @endif
                            
                            <!-- Order Completed -->
                            @if($order->status === 'completed')
                                <div class="mb-8 flex items-center">
                                    <div class="absolute -left-3.5">
                                        <div class="h-7 w-7 rounded-full bg-green-500 flex items-center justify-center">
                                            <i class="fas fa-check text-white text-xs"></i>
                                        </div>
                                    </div>
                                    <div class="ml-6">
                                        <h3 class="text-sm font-semibold text-gray-900">Order Completed</h3>
                                        <p class="text-xs text-gray-500">{{ $order->updated_at->format('M d, Y h:i A') }}</p>
                                        <p class="mt-1 text-sm text-gray-700">Your order has been completed successfully.</p>
                                    </div>
                                </div>
                            @endif
                            
                            <!-- Order Cancelled -->
                            @if($order->status === 'cancelled')
                                <div class="mb-8 flex items-center">
                                    <div class="absolute -left-3.5">
                                        <div class="h-7 w-7 rounded-full bg-red-500 flex items-center justify-center">
                                            <i class="fas fa-times text-white text-xs"></i>
                                        </div>
                                    </div>
                                    <div class="ml-6">
                                        <h3 class="text-sm font-semibold text-gray-900">Order Cancelled</h3>
                                        <p class="text-xs text-gray-500">{{ $order->updated_at->format('M d, Y h:i A') }}</p>
                                        <p class="mt-1 text-sm text-gray-700">Your order has been cancelled.</p>
                                    </div>
                                </div>
                            @endif
                            
                            <!-- Next Steps -->
                            @if($order->status === 'pending')
                                <div class="mb-8 flex items-center">
                                    <div class="absolute -left-3.5">
                                        <div class="h-7 w-7 rounded-full bg-gray-300 flex items-center justify-center">
                                            <i class="fas fa-clock text-white text-xs"></i>
                                        </div>
                                    </div>
                                    <div class="ml-6">
                                        <h3 class="text-sm font-semibold text-gray-900">Awaiting Confirmation</h3>
                                        <p class="mt-1 text-sm text-gray-700">Your order is pending confirmation from our team.</p>
                                    </div>
                                </div>
                            @elseif($order->status === 'processing')
                                <div class="mb-8 flex items-center">
                                    <div class="absolute -left-3.5">
                                        <div class="h-7 w-7 rounded-full bg-gray-300 flex items-center justify-center">
                                            <i class="fas fa-credit-card text-white text-xs"></i>
                                        </div>
                                    </div>
                                    <div class="ml-6">
                                        <h3 class="text-sm font-semibold text-gray-900">Payment Required</h3>
                                        <p class="mt-1 text-sm text-gray-700">Please complete your payment to proceed with the order.</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
