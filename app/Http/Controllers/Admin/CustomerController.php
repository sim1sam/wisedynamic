<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules;

class CustomerController extends Controller
{
    /**
     * Display a listing of customers.
     */
    public function index()
    {
        try {
            // Debug log to see if this method is being called
            Log::info('CustomerController@index method called');
            
            // Get all customers (users with role 'customer')
            $customers = User::where('role', 'customer')
                ->orderBy('created_at', 'desc')
                ->get();
            
            // Debug log the number of customers found
            Log::info('Found ' . $customers->count() . ' customers');
            
            return view('admin.customers.index', [
                'customers' => $customers
            ]);
        } catch (\Exception $e) {
            Log::error('Error loading customers', [
                'error' => $e->getMessage()
            ]);
            
            return redirect()->route('admin.dashboard')
                ->with('error', 'Failed to load customers: ' . $e->getMessage());
        }
    }
    
    /**
     * Show the form for creating a new customer.
     */
    public function create()
    {
        try {
            return view('admin.customers.create');
        } catch (\Exception $e) {
            Log::error('Error showing customer create form', [
                'error' => $e->getMessage()
            ]);
            
            return redirect()->route('admin.customers.index')
                ->with('error', 'Failed to show customer create form: ' . $e->getMessage());
        }
    }
    
    /**
     * Store a newly created customer in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
                'phone' => ['nullable', 'string', 'max:20'],
                'address' => ['nullable', 'string', 'max:255'],
                'city' => ['nullable', 'string', 'max:100'],
                'state' => ['nullable', 'string', 'max:100'],
                'postal_code' => ['nullable', 'string', 'max:20'],
                'country' => ['nullable', 'string', 'max:100'],
                'status' => ['required', 'in:active,blocked'],
            ]);
            
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'phone' => $validated['phone'] ?? null,
                'address' => $validated['address'] ?? null,
                'city' => $validated['city'] ?? null,
                'state' => $validated['state'] ?? null,
                'postal_code' => $validated['postal_code'] ?? null,
                'country' => $validated['country'] ?? null,
                'role' => 'customer',
                'status' => $validated['status'],
            ]);
            
            return redirect()->route('admin.customers.index')
                ->with('success', 'Customer created successfully.');
        } catch (\Exception $e) {
            Log::error('Error creating customer', [
                'error' => $e->getMessage()
            ]);
            
            return redirect()->route('admin.customers.create')
                ->with('error', 'Failed to create customer: ' . $e->getMessage())
                ->withInput();
        }
    }
    
    /**
     * Display the specified customer.
     */
    public function show(User $customer)
    {
        try {
            // Load customer's orders
            $packageOrders = $customer->packageOrders()->orderBy('created_at', 'desc')->get();
            $serviceOrders = $customer->serviceOrders()->orderBy('created_at', 'desc')->get();
            
            // Load customer's fund requests
            $fundRequests = $customer->fundRequests()->orderBy('created_at', 'desc')->get();
            
            // Calculate fund statistics
            $totalFundRequested = $fundRequests->sum('amount');
            $totalFundApproved = $fundRequests->where('status', 'approved')->sum('amount');
            $pendingFundRequests = $fundRequests->where('status', 'pending')->sum('amount');
            
            return view('admin.customers.show', [
                'customer' => $customer,
                'packageOrders' => $packageOrders,
                'serviceOrders' => $serviceOrders,
                'fundRequests' => $fundRequests,
                'totalFundRequested' => $totalFundRequested,
                'totalFundApproved' => $totalFundApproved,
                'pendingFundRequests' => $pendingFundRequests
            ]);
        } catch (\Exception $e) {
            Log::error('Error viewing customer', [
                'error' => $e->getMessage(),
                'customer_id' => $customer->id
            ]);
            
            return redirect()->route('admin.customers.index')
                ->with('error', 'Failed to view customer: ' . $e->getMessage());
        }
    }
    
    /**
     * Show the form for editing the specified customer.
     */
    public function edit(User $customer)
    {
        try {
            return view('admin.customers.edit', [
                'customer' => $customer
            ]);
        } catch (\Exception $e) {
            Log::error('Error editing customer', [
                'error' => $e->getMessage(),
                'customer_id' => $customer->id
            ]);
            
            return redirect()->route('admin.customers.index')
                ->with('error', 'Failed to edit customer: ' . $e->getMessage());
        }
    }
    
    /**
     * Update the specified customer in storage.
     */
    public function update(Request $request, User $customer)
    {
        try {
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $customer->id],
                'phone' => ['nullable', 'string', 'max:20'],
                'address' => ['nullable', 'string', 'max:255'],
                'city' => ['nullable', 'string', 'max:100'],
                'state' => ['nullable', 'string', 'max:100'],
                'postal_code' => ['nullable', 'string', 'max:20'],
                'country' => ['nullable', 'string', 'max:100'],
                'status' => ['required', 'in:active,blocked'],
            ]);
            
            $customer->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'address' => $validated['address'] ?? null,
                'city' => $validated['city'] ?? null,
                'state' => $validated['state'] ?? null,
                'postal_code' => $validated['postal_code'] ?? null,
                'country' => $validated['country'] ?? null,
                'status' => $validated['status'],
            ]);
            
            // Update password if provided
            if ($request->filled('password')) {
                $request->validate([
                    'password' => ['required', 'confirmed', Rules\Password::defaults()],
                ]);
                
                $customer->update([
                    'password' => Hash::make($request->password),
                ]);
            }
            
            return redirect()->route('admin.customers.show', $customer)
                ->with('success', 'Customer updated successfully.');
        } catch (\Exception $e) {
            Log::error('Error updating customer', [
                'error' => $e->getMessage(),
                'customer_id' => $customer->id
            ]);
            
            return redirect()->route('admin.customers.edit', $customer)
                ->with('error', 'Failed to update customer: ' . $e->getMessage())
                ->withInput();
        }
    }
    
    /**
     * Update the status of the specified customer.
     */
    public function updateStatus(Request $request, User $customer)
    {
        try {
            $validated = $request->validate([
                'status' => ['required', 'in:active,blocked'],
            ]);
            
            $customer->update([
                'status' => $validated['status']
            ]);
            
            $statusText = $validated['status'] === 'active' ? 'activated' : 'blocked';
            
            return redirect()->route('admin.customers.show', $customer)
                ->with('success', 'Customer has been ' . $statusText . ' successfully.');
        } catch (\Exception $e) {
            Log::error('Error updating customer status', [
                'error' => $e->getMessage(),
                'customer_id' => $customer->id
            ]);
            
            return redirect()->route('admin.customers.show', $customer)
                ->with('error', 'Failed to update customer status: ' . $e->getMessage());
        }
    }
    
    /**
     * Remove the specified customer from storage.
     */
    public function destroy(User $customer)
    {
        try {
            // Check if customer has any orders
            $hasOrders = $customer->packageOrders()->count() > 0 || $customer->serviceOrders()->count() > 0;
            
            if ($hasOrders) {
                return redirect()->route('admin.customers.show', $customer)
                    ->with('error', 'Cannot delete customer with existing orders. Consider blocking the customer instead.');
            }
            
            $customer->delete();
            
            return redirect()->route('admin.customers.index')
                ->with('success', 'Customer deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Error deleting customer', [
                'error' => $e->getMessage(),
                'customer_id' => $customer->id
            ]);
            
            return redirect()->route('admin.customers.show', $customer)
                ->with('error', 'Failed to delete customer: ' . $e->getMessage());
        }
    }
}
