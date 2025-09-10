@extends('layouts.app')

@section('content')
<header class="theme-gradient text-white pt-28 pb-12">
    <div class="container mx-auto px-6">
        <h1 class="text-3xl md:text-4xl font-extrabold">Checkout</h1>
        <p class="mt-2 text-white/90">Enter your customer details and billing address</p>
    </div>
</header>

<section class="py-12 bg-white">
    <div class="container mx-auto px-6 grid lg:grid-cols-3 gap-8">
        <!-- Read-only Details -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold mb-4">Review Details</h2>
                @php
                    $cart = $cart ?? [];
                    // Ensure these variables are always defined
                    $itemType = $cart['item_type'] ?? 'package';
                    $itemKey = $cart['item_key'] ?? '';
                    $isService = $itemType === 'service';
                    $isMarketing = in_array($itemKey, ['social','seo','ads']);
                @endphp
                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <div class="text-sm text-gray-600">{{ ($cart['item_type'] ?? 'package') === 'service' ? 'Service' : 'Package' }}</div>
                        <div class="text-gray-900 font-medium">{{ ucfirst($cart['item_key'] ?? '—') }}</div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-600">Total</div>
                        <div class="text-gray-900 font-semibold">BDT {{ number_format((int)($cart['amount'] ?? 0)) }}/-</div>
                    </div>
                </div>

                <hr class="my-6">
                
                <h3 class="text-lg font-semibold mb-2">{{ $isService ? 'Service Details' : ($isMarketing ? 'Marketing Info' : 'Project Details') }}</h3>
                <div class="grid md:grid-cols-2 gap-4 text-sm text-gray-700">
                    @if($isService)
                        <div>{{ $isMarketing ? 'Business/Page' : 'Project Name' }}: <span class="text-gray-900">{{ $cart['website_name'] ?? '—' }}</span></div>
                        <div>Type: <span class="text-gray-900">{{ $cart['website_type'] ?? '—' }}</span></div>
                    @elseif($isMarketing)
                        <div>Business/Page: <span class="text-gray-900">{{ $cart['website_name'] ?? '—' }}</span></div>
                        <div>Type: <span class="text-gray-900">{{ $cart['website_type'] ?? '—' }}</span></div>
                    @else
                        <div>Website/Business: <span class="text-gray-900">{{ $cart['website_name'] ?? '—' }}</span></div>
                        <div>Type: <span class="text-gray-900">{{ $cart['website_type'] ?? '—' }}</span></div>
                        <div>Pages: <span class="text-gray-900">{{ $cart['page_count'] ?? '—' }}</span></div>
                    @endif
                    <div class="md:col-span-2">{{ $isService ? 'Requirements' : 'Notes' }}: <span class="text-gray-900 whitespace-pre-line">{{ $cart['notes'] ?? '—' }}</span></div>
                </div>

                <hr class="my-6">
                <h3 class="text-lg font-semibold mb-2">Customer</h3>
                <div class="grid md:grid-cols-2 gap-4 text-sm text-gray-700">
                    <div>Name: <span class="text-gray-900">{{ $customer['full_name'] ?? '—' }}</span></div>
                    <div>Company: <span class="text-gray-900">{{ $customer['company'] ?? '—' }}</span></div>
                    <div>Email: <span class="text-gray-900">{{ $customer['email'] ?? '—' }}</span></div>
                    <div>Phone: <span class="text-gray-900">{{ $customer['phone'] ?? '—' }}</span></div>
                </div>

                <hr class="my-6">
                <h3 class="text-lg font-semibold mb-2">Billing Address</h3>
                <div class="text-sm text-gray-700">
                    <div>{{ $billing['address_line1'] ?? '—' }}</div>
                    @if(!empty($billing['address_line2']))
                        <div>{{ $billing['address_line2'] }}</div>
                    @endif
                    <div>{{ ($billing['city'] ?? '—') }}, {{ $billing['state'] ?? '' }} {{ $billing['postal_code'] ?? '' }}</div>
                    <div>{{ $billing['country'] ?? '' }}</div>
                </div>

                <div class="flex gap-3 mt-6">
                    @if(($cart['item_type'] ?? 'package') === 'service')
                        <a href="{{ route('cart.show', ['service' => $cart['item_key'] ?? null]) }}" class="btn-outline-primary px-6 py-3 rounded-full font-semibold">Back to Edit</a>
                    @else
                        <a href="{{ route('cart.show', ['package' => $cart['item_key'] ?? null]) }}" class="btn-outline-primary px-6 py-3 rounded-full font-semibold">Back to Edit</a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Order Summary -->
        <aside>
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-4">Order Summary</h3>
                <div class="flex items-center justify-between mb-2">
                    <span class="text-gray-700">Total</span>
                    <span class="text-2xl font-bold">BDT {{ number_format((int)($cart['amount'] ?? 0)) }}/-</span>
                </div>
                <p class="text-xs text-gray-500 mb-4">VAT/Tax included</p>
                <form action="{{ route('checkout.place') }}" method="POST" class="space-y-3">
                    @csrf
                    <button type="submit" class="btn-primary w-full text-center px-6 py-3 rounded-full font-semibold">Place Order</button>
                </form>
            </div>
        </aside>
    </div>
    
</section>
@endsection
