<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CartController extends Controller
{
    // Show the cart page and prefill based on selected package
    public function show(Request $request)
    {
        $package = $request->query('package');

        // Static package catalog (for now)
        $catalog = [
            // Website packages
            'startup'   => ['title' => 'Startup (Website)', 'amount' => 20000],
            'streamline'=> ['title' => 'Streamline (Website)', 'amount' => 50000],
            'scale'     => ['title' => 'Scale (Website)', 'amount' => 80000],
            'stable'    => ['title' => 'Stable (Website)', 'amount' => 200000],
            // Marketing packages
            'social'    => ['title' => 'Social Media Marketing', 'amount' => 12000],
            'seo'       => ['title' => 'SEO Growth Plan', 'amount' => 18000],
            'ads'       => ['title' => 'Google Ads Campaign', 'amount' => 15000],
        ];

        $selected = $package && isset($catalog[$package]) ? array_merge($catalog[$package], ['key' => $package]) : null;
        $packageType = null;
        if ($selected) {
            $packageType = in_array($selected['key'], ['social','seo','ads']) ? 'marketing' : 'website';
        }

        return view('frontend.cart.index', [
            'selected' => $selected,
            'catalog'  => $catalog,
            'packageType' => $packageType,
            'customer' => session('customer'),
            'billing' => session('billing'),
        ]);
    }

    // Accept cart form, store to session, go to checkout
    public function store(Request $request)
    {
        // Cart (package and project/marketing details)
        $cart = $request->validate([
            'package_key'   => 'required|string',
            'website_name'  => 'nullable|string|max:255',
            'website_type'  => 'nullable|string|max:255',
            'page_count'    => 'nullable|integer|min:1',
            'notes'         => 'nullable|string|max:2000',
            'amount'        => 'required|integer|min:0',
            // Marketing specific
            'page_url'      => 'nullable|url|max:255',
            'ad_budget'     => 'nullable|integer|min:0',
        ]);

        // Customer & billing details now collected on cart page
        $validated = $request->validate([
            // Customer
            'full_name'     => 'required|string|max:255',
            'email'         => 'required|email|max:255',
            'phone'         => 'required|string|max:50',
            'company'       => 'nullable|string|max:255',
            // Billing
            'address_line1' => 'required|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'city'          => 'required|string|max:120',
            'state'         => 'nullable|string|max:120',
            'postal_code'   => 'required|string|max:20',
            'country'       => 'required|string|max:120',
        ]);

        session([
            'cart' => $cart,
            'customer' => [
                'full_name' => $validated['full_name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'company' => $validated['company'] ?? null,
            ],
            'billing' => [
                'address_line1' => $validated['address_line1'],
                'address_line2' => $validated['address_line2'] ?? null,
                'city' => $validated['city'],
                'state' => $validated['state'] ?? null,
                'postal_code' => $validated['postal_code'],
                'country' => $validated['country'],
            ],
        ]);

        return redirect()->route('checkout.show');
    }

    // Checkout summary page
    public function checkout(Request $request)
    {
        $cart = session('cart');
        if (!$cart) {
            return redirect()->route('cart.show');
        }

        return view('frontend.checkout.index', [
            'cart' => $cart,
            'customer' => session('customer'),
            'billing' => session('billing'),
        ]);
    }

    // Place order: collect customer + billing details
    public function place(Request $request)
    {
        $cart = session('cart');
        $customer = session('customer');
        $billing = session('billing');
        if (!$cart || !$customer || !$billing) {
            return redirect()->route('cart.show');
        }

        // Here we would persist the order and optionally take payment

        return redirect(url('/#contact') . '?package=' . urlencode($cart['package_key']) . '&order=placed')
            ->with('success', 'Order placed. We will contact you shortly.');
    }
}
