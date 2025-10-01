@extends('layouts.app')

@section('title', 'SSL Payment')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-md mx-auto bg-white rounded-lg shadow-md p-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">SSL Payment</h2>
        
        <div class="mb-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-2">Order Details</h3>
            <div class="bg-gray-50 p-4 rounded-lg">
                <p><strong>Type:</strong> {{ ucfirst($type) }}</p>
                <p><strong>Order ID:</strong> #{{ $order->id }}</p>
                @if($type === 'fund')
                    <p><strong>Amount:</strong> BDT {{ number_format($order->amount, 2) }}</p>
                @elseif($type === 'custom-service')
                    <p><strong>Total Amount:</strong> BDT {{ number_format($order->total_amount, 2) }}</p>
                @else
                    <p><strong>Amount:</strong> BDT {{ number_format($order->amount, 2) }}</p>
                    <p><strong>Paid:</strong> BDT {{ number_format($order->paid_amount ?? 0, 2) }}</p>
                    <p><strong>Due:</strong> BDT {{ number_format($order->due_amount ?? $order->amount, 2) }}</p>
                @endif
            </div>
        </div>

        <form method="POST" action="{{ route('customer.payment.ssl', ['type' => $type, 'id' => $id]) }}">
            @csrf
            
            <div class="mb-4">
                <label for="payment_amount" class="block text-sm font-medium text-gray-700 mb-2">
                    Payment Amount (BDT)
                </label>
                <input 
                    type="number" 
                    id="payment_amount" 
                    name="payment_amount" 
                    step="0.01" 
                    min="1"
                    @if($type === 'fund')
                        max="{{ $order->amount }}"
                        value="{{ $order->amount }}"
                    @elseif($type === 'custom-service')
                        max="{{ $order->total_amount }}"
                        value="{{ $order->total_amount }}"
                    @else
                        max="{{ $order->due_amount ?? $order->amount }}"
                        value="{{ $order->due_amount ?? $order->amount }}"
                    @endif
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required
                >
                @error('payment_amount')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex space-x-4">
                <button 
                    type="submit" 
                    class="flex-1 bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                    Pay with SSL Commerz
                </button>
                
                <a 
                    href="{{ route('customer.payment.options', ['type' => $type, 'id' => $id]) }}" 
                    class="flex-1 bg-gray-300 text-gray-700 py-2 px-4 rounded-md text-center hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500"
                >
                    Back
                </a>
            </div>
        </form>
    </div>
</div>
@endsection