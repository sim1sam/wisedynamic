<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomerRequest;
use App\Models\User;
use Illuminate\Http\Request;

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
        return view('admin.requests.show', compact('customerRequest'));
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
}
