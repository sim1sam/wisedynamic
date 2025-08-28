<?php

namespace App\Http\Controllers;

use App\Models\CustomerRequest;
use Illuminate\Http\Request;

class RequestController extends Controller
{
    public function index(Request $request)
    {
        $requests = CustomerRequest::where('user_id', $request->user()->id)
            ->latest()
            ->paginate(10);
        return view('frontend.account.requests.index', compact('requests'));
    }

    public function create()
    {
        return view('frontend.account.requests.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        CustomerRequest::create([
            'user_id' => $request->user()->id,
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'status' => CustomerRequest::STATUS_PENDING,
        ]);

        return redirect()->route('home')->with('success', 'Request submitted successfully.');
    }
}
