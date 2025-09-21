<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomerRequest;
use App\Models\Service;
use App\Models\ServiceOrder;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerRequestController extends Controller
{
    public function index(Request $request)
    {
        $requests = CustomerRequest::with('user')->latest()->paginate(20);
        return view('admin.requests.index', compact('requests'));
    }

    public function updateStatus(Request $request, CustomerRequest $customerRequest)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:pending,in_progress,done'],
        ]);
        $customerRequest->update(['status' => $validated['status']]);
        return back()->with('success', 'Status updated');
    }

    public function create()
    {
        $users = User::where('is_admin', false)->orderBy('name')->get(['id','name','email']);
        return view('admin.requests.create', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => ['required','exists:users,id'],
            'page_name' => ['required','string','max:255'],
            'social_media' => ['required','in:facebook,instagram,tiktok,twitter,linkedin,youtube'],
            'ads_budget_bdt' => ['required','numeric','min:0'],
            'days' => ['required','integer','min:1'],
            'post_link' => ['nullable','url','max:2048'],
        ]);

        CustomerRequest::create([
            'user_id' => $validated['user_id'],
            'title' => $validated['page_name'],
            'description' => null,
            'page_name' => $validated['page_name'],
            'social_media' => $validated['social_media'],
            'ads_budget_bdt' => $validated['ads_budget_bdt'],
            'days' => $validated['days'],
            'post_link' => $validated['post_link'] ?? null,
            'status' => CustomerRequest::STATUS_PENDING,
        ]);

        return redirect()->route('admin.requests.index')->with('success', 'Request created successfully');
    }

    public function show(CustomerRequest $customerRequest)
    {
        try {
            // Get all services regardless of status
            $services = Service::orderBy('title')->get();
        } catch (\Exception $e) {
            // If there's an error with the services table, provide an empty collection
            $services = collect([]);
        }
        
        return view('admin.requests.show', compact('customerRequest', 'services'));
    }

    public function edit(CustomerRequest $customerRequest)
    {
        $users = User::where('is_admin', false)->orderBy('name')->get(['id','name','email']);
        return view('admin.requests.edit', compact('customerRequest','users'));
    }

    public function update(Request $request, CustomerRequest $customerRequest)
    {
        $validated = $request->validate([
            'user_id' => ['required','exists:users,id'],
            'page_name' => ['required','string','max:255'],
            'social_media' => ['required','in:facebook,instagram,tiktok,twitter,linkedin,youtube'],
            'ads_budget_bdt' => ['required','numeric','min:0'],
            'days' => ['required','integer','min:1'],
            'post_link' => ['nullable','url','max:2048'],
            'status' => ['required','in:pending,in_progress,done'],
        ]);

        $customerRequest->update([
            'user_id' => $validated['user_id'],
            'title' => $validated['page_name'],
            'page_name' => $validated['page_name'],
            'social_media' => $validated['social_media'],
            'ads_budget_bdt' => $validated['ads_budget_bdt'],
            'days' => $validated['days'],
            'post_link' => $validated['post_link'] ?? null,
            'status' => $validated['status'],
        ]);

        return redirect()->route('admin.requests.show', $customerRequest)->with('success', 'Request updated successfully');
    }
    
    /**
     * Convert a customer request to a service order
     */
    public function convertToServiceOrder(Request $request, CustomerRequest $customerRequest)
    {
        try {
            // Validate the request
            $validated = $request->validate([
                'service_id' => ['required', 'exists:services,id'],
                'amount' => ['required', 'numeric', 'min:0'],
            ]);
            
            // Log the request data for debugging
            \Log::info('Converting customer request to service order', [
                'request_id' => $customerRequest->id,
                'service_id' => $validated['service_id'],
                'amount' => $validated['amount'],
            ]);
            
            // Start a database transaction
            DB::beginTransaction();
            
            // Get the service
            $service = Service::findOrFail($validated['service_id']);
            \Log::info('Service found', ['service' => $service->title]);
            
            // Get the user
            $user = $customerRequest->user;
            if (!$user) {
                throw new \Exception('Customer not found for this request');
            }
            \Log::info('User found', ['user' => $user->name]);
            
            // Create the service order with direct DB insertion to avoid model issues
            $serviceOrderId = DB::table('service_orders')->insertGetId([
                'user_id' => $user->id,
                'service_id' => $service->id,
                'service_name' => $service->title,
                'amount' => $validated['amount'],
                'full_name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone ?? '',
                'address_line1' => $user->address ?? '',
                'city' => $user->city ?? '',
                'state' => $user->state ?? '',
                'postal_code' => $user->postal_code ?? '',
                'country' => $user->country ?? '',
                'project_name' => $customerRequest->page_name,
                'project_type' => $customerRequest->social_media,
                'requirements' => 'Converted from customer request. Budget: ' . $customerRequest->ads_budget_bdt . ' BDT, Days: ' . $customerRequest->days,
                'notes' => $customerRequest->post_link ? 'Post link: ' . $customerRequest->post_link : '',
                'status' => 'pending',
                'paid_amount' => 0,
                'due_amount' => $validated['amount'],
                'total_installments' => 1,
                'current_installment' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            // Get the service order
            $serviceOrder = ServiceOrder::find($serviceOrderId);
            
            \Log::info('Service order created', ['order_id' => $serviceOrder->id]);
            
            // Update the customer request status to done and mark as converted
            $customerRequest->update([
                'status' => CustomerRequest::STATUS_DONE,
                'is_converted' => true,
                'service_order_id' => $serviceOrderId,
                'converted_at' => now(),
            ]);
            
            // Commit the transaction
            DB::commit();
            
            // Redirect to service orders index instead of show page to avoid potential route issues
            return redirect()->route('admin.service-orders.index')
                ->with('success', 'Customer request successfully converted to service order #' . $serviceOrderId . '.');
        } catch (\Exception $e) {
            // Rollback the transaction if something goes wrong
            DB::rollBack();
            
            \Log::error('Failed to convert request to service order', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('admin.requests.show', $customerRequest)
                ->with('error', 'Failed to convert request to service order: ' . $e->getMessage());
        }
    }
}
