<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CartController extends Controller
{
    // Show the cart page and prefill based on selected package or service
    public function show(Request $request)
    {
        $packageSlug = $request->query('package');
        $serviceSlug = $request->query('service');
        
        // Initialize variables
        $dbPackage = null;
        $dbService = null;
        $selected = null;
        $itemType = null;
        $packageType = null;
        
        // Check for service first
        if ($serviceSlug) {
            $dbService = \App\Models\Service::where('slug', $serviceSlug)->where('status', true)->first();
            
            if ($dbService) {
                $itemType = 'service';
                $selected = [
                    'key' => $dbService->slug,
                    'title' => $dbService->title,
                    'amount' => intval($dbService->price),
                    'type' => 'service'
                ];
            }
        }
        // If no service, check for package
        elseif ($packageSlug) {
            $dbPackage = \App\Models\Package::where('slug', $packageSlug)->where('status', true)->first();
            
            if ($dbPackage) {
                $itemType = 'package';
                // Get package category to determine type
                $category = $dbPackage->category;
                $packageType = $category && $category->name === 'Digital Marketing' ? 'marketing' : 'website';
                
                $selected = [
                    'key' => $dbPackage->slug,
                    'title' => $dbPackage->title,
                    'amount' => intval($dbPackage->price),
                    'type' => 'package'
                ];
            }
        }
        
        // Fallback to static catalog if package not found in database
        if (!$selected && $packageSlug) {
            // Static package catalog as fallback
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
            
            if (isset($catalog[$packageSlug])) {
                $itemType = 'package';
                $selected = array_merge($catalog[$packageSlug], ['key' => $packageSlug, 'type' => 'package']);
                $packageType = in_array($packageSlug, ['social','seo','ads']) ? 'marketing' : 'website';
            }
        }

        // Define the catalog for the view (needed for the fallback in the view)
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
        // Cart (package/service and project details)
        $cart = $request->validate([
            'item_type'     => 'required|string|in:package,service',
            'item_key'      => 'required|string',
            'website_name'  => 'nullable|string|max:255',
            'website_type'  => 'nullable|string|max:255',
            'page_count'    => 'nullable|integer|min:1',
            'notes'         => 'nullable|string|max:2000',
            'amount'        => 'required|numeric',
        ]);
        
        // Convert amount to integer
        $cart['amount'] = intval($cart['amount']);

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
        
        // Ensure item_type is always defined
        if (!isset($cart['item_type'])) {
            $cart['item_type'] = 'package';
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
        
        // Check if this is a package or service order
        $itemType = $cart['item_type'] ?? 'package';
        $itemKey = $cart['item_key'];
        $amount = $cart['amount'];
        
        if ($itemType === 'package') {
            // Handle package order
            $package = null;
            $packageName = $itemKey;
            
            // Try to find the package in the database
            $dbPackage = \App\Models\Package::where('slug', $itemKey)->first();
            if ($dbPackage) {
                $package = $dbPackage;
                $packageName = $dbPackage->title;
                $amount = $dbPackage->price;
            }
            
            // Create the package order
            $order = \App\Models\PackageOrder::create([
                'package_id' => $package ? $package->id : null,
                'package_name' => $packageName,
                'amount' => $amount,
                
                // Customer information
                'full_name' => $customer['full_name'],
                'email' => $customer['email'],
                'phone' => $customer['phone'],
                'company' => $customer['company'] ?? null,
                
                // Billing information
                'address_line1' => $billing['address_line1'],
                'address_line2' => $billing['address_line2'] ?? null,
                'city' => $billing['city'],
                'state' => $billing['state'] ?? null,
                'postal_code' => $billing['postal_code'],
                'country' => $billing['country'],
                
                // Project details
                'website_name' => $cart['website_name'] ?? null,
                'website_type' => $cart['website_type'] ?? null,
                'page_count' => $cart['page_count'] ?? null,
                'notes' => $cart['notes'] ?? null,
                
                // Set initial status
                'status' => 'pending',
            ]);
            
            $redirectParam = 'package=' . urlencode($itemKey);
        } else {
            // Handle service order
            $service = null;
            $serviceName = $itemKey;
            
            // Try to find the service in the database
            $dbService = \App\Models\Service::where('slug', $itemKey)->first();
            if ($dbService) {
                $service = $dbService;
                $serviceName = $dbService->title;
                $amount = $dbService->price;
            }
            
            // Create the service order
            $order = \App\Models\ServiceOrder::create([
                'service_id' => $service ? $service->id : null,
                'service_name' => $serviceName,
                'amount' => $amount,
                
                // Customer information
                'full_name' => $customer['full_name'],
                'email' => $customer['email'],
                'phone' => $customer['phone'],
                'company' => $customer['company'] ?? null,
                
                // Billing information
                'address_line1' => $billing['address_line1'],
                'address_line2' => $billing['address_line2'] ?? null,
                'city' => $billing['city'],
                'state' => $billing['state'] ?? null,
                'postal_code' => $billing['postal_code'],
                'country' => $billing['country'],
                
                // Project details
                'project_name' => $cart['website_name'] ?? null,
                'project_type' => $cart['website_type'] ?? null,
                'requirements' => $cart['notes'] ?? null,
                'notes' => null,
                
                // Set initial status
                'status' => 'pending',
            ]);
            
            $redirectParam = 'service=' . urlencode($itemKey);
        }
        
        // Clear the cart session
        session()->forget(['cart', 'customer', 'billing']);
        
        return redirect(url('/#contact') . '?' . $redirectParam . '&order=placed')
            ->with('success', 'Order placed. We will contact you shortly.');
    }
}
