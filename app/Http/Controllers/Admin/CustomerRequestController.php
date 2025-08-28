<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomerRequest;
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
}
