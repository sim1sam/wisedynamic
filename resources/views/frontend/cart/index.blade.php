@extends('layouts.app')

@section('content')
<header class="theme-gradient text-white pt-28 pb-12">
    <div class="container mx-auto px-6">
        <h1 class="text-3xl md:text-4xl font-extrabold">Cart</h1>
        <p class="mt-2 text-white/90">Provide a few details and proceed to checkout</p>
    </div>
</header>

<section class="py-12 bg-white">
    <div class="container mx-auto px-6 grid lg:grid-cols-3 gap-8">
        <!-- Form -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold mb-4">Project Details</h2>
                @if ($errors->any())
                    <div class="mb-4 p-3 border border-red-200 bg-red-50 text-red-700 rounded">
                        <ul class="list-disc ml-5 text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form action="{{ route('cart.store') }}" method="POST" class="space-y-6">
                    @csrf
                    <input type="hidden" name="item_type" value="{{ $selected['type'] ?? 'package' }}">
                    <input type="hidden" name="item_key" value="{{ $selected['key'] ?? request('package') ?? request('service') }}">
                    <input type="hidden" name="amount" value="{{ intval($selected['amount'] ?? ($catalog[request('package')]['amount'] ?? 0)) }}">

                    @if(($packageType ?? null) === 'marketing' || (isset($selected['key']) && in_array($selected['key'], ['social', 'seo', 'ads'])))
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Business/Page Name</label>
                            <input type="text" name="website_name" class="w-full border rounded-lg px-4 py-2" placeholder="e.g., Wise Dynamic" value="{{ old('website_name') }}">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Marketing Type</label>
                            <input type="text" name="website_type" class="w-full border rounded-lg px-4 py-2" placeholder="e.g., Social Media, SEO, Google Ads" value="{{ old('website_type') }}">
                        </div>
                    @else
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Website / Business Name</label>
                            <input type="text" name="website_name" class="w-full border rounded-lg px-4 py-2" placeholder="e.g., Wise Dynamic" value="{{ old('website_name') }}">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Website Type</label>
                            <input type="text" name="website_type" class="w-full border rounded-lg px-4 py-2" placeholder="e.g., Portfolio, E‑commerce, Blog" value="{{ old('website_type') }}">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">How many pages?</label>
                            <input type="number" min="1" name="page_count" class="w-full border rounded-lg px-4 py-2" placeholder="e.g., 5" value="{{ old('page_count') }}">
                        </div>
                    @endif

                    <hr class="my-6">
                    <h3 class="text-lg font-semibold">Customer Information</h3>
                    <div class="grid md:grid-cols-2 gap-4 mt-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Full name</label>
                            <input type="text" name="full_name" class="w-full border rounded-lg px-4 py-2" value="{{ old('full_name') }}" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Company (optional)</label>
                            <input type="text" name="company" class="w-full border rounded-lg px-4 py-2" value="{{ old('company') }}">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" name="email" class="w-full border rounded-lg px-4 py-2" value="{{ old('email') }}" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                            <input type="text" name="phone" class="w-full border rounded-lg px-4 py-2" value="{{ old('phone') }}" required>
                        </div>
                    </div>

                    <div class="mt-4">
                        <h3 class="text-lg font-semibold">Billing Address</h3>
                        <div class="grid md:grid-cols-2 gap-4 mt-3">
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Address line 1</label>
                                <input type="text" name="address_line1" class="w-full border rounded-lg px-4 py-2" value="{{ old('address_line1') }}" required>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Address line 2 (optional)</label>
                                <input type="text" name="address_line2" class="w-full border rounded-lg px-4 py-2" value="{{ old('address_line2') }}">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">City</label>
                                <input type="text" name="city" class="w-full border rounded-lg px-4 py-2" value="{{ old('city') }}" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">State / Province</label>
                                <input type="text" name="state" class="w-full border rounded-lg px-4 py-2" value="{{ old('state') }}">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Postal code</label>
                                <input type="text" name="postal_code" class="w-full border rounded-lg px-4 py-2" value="{{ old('postal_code') }}" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Country</label>
                                <input type="text" name="country" class="w-full border rounded-lg px-4 py-2" value="{{ old('country') }}" required>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Notes (optional)</label>
                        <textarea name="notes" rows="4" class="w-full border rounded-lg px-4 py-2" placeholder="Tell us more about your requirements...">{{ old('notes') }}</textarea>
                    </div>

                    <div class="flex gap-3 pt-2">
                        <a href="{{ route('packages') }}" class="btn-outline-primary px-6 py-3 rounded-full font-semibold">Back to Packages</a>
                        <button type="submit" class="btn-primary px-6 py-3 rounded-full font-semibold">Proceed to Checkout</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Summary -->
        <aside>
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-4">Order Summary</h3>
                @if($selected)
                    <div class="mb-4">
                        <p class="text-gray-700 font-medium">Selected Package</p>
                        <p class="text-gray-900">{{ $selected['title'] }}</p>
                    </div>
                    <div class="mb-4">
                        <p class="text-gray-700 font-medium">Total Amount</p>
                        <p class="text-2xl font-bold">BDT {{ number_format($selected['amount']) }}/-</p>
                    </div>
                @else
                    <p class="text-gray-600">No package selected. Please go back and select a package.</p>
                @endif
                <div class="text-sm text-gray-500">Prices shown are current; taxes or add-ons (if any) will be finalized during checkout.</div>
            </div>
        </aside>
    </div>
</section>
@endsection
