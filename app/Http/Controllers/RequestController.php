<?php

namespace App\Http\Controllers;

use App\Models\CustomerRequest;
use Illuminate\Http\Request;

class RequestController extends Controller
{
    public function index(Request $request)
    {
        $userId = $request->user()->id;
        $status = $request->query('status'); // pending|in_progress|done|null
        $q = trim((string) $request->query('q', ''));

        $base = CustomerRequest::where('user_id', $userId);

        $query = (clone $base);
        if ($status && in_array($status, ['pending','in_progress','done'], true)) {
            $query->where('status', $status);
        }
        if ($q !== '') {
            $query->where(function($w) use ($q){
                $w->where('page_name', 'like', "%{$q}%")
                  ->orWhere('social_media', 'like', "%{$q}%")
                  ->orWhere('post_link', 'like', "%{$q}%");
            });
        }

        $requests = $query->latest()->paginate(10)->withQueryString();

        // Counts for filters
        $counts = [
            'all' => (clone $base)->count(),
            'pending' => (clone $base)->where('status', CustomerRequest::STATUS_PENDING)->count(),
            'in_progress' => (clone $base)->where('status', CustomerRequest::STATUS_IN_PROGRESS)->count(),
            'done' => (clone $base)->where('status', CustomerRequest::STATUS_DONE)->count(),
        ];

        return view('frontend.customer.requests.index', compact('requests','counts','status','q'));
    }

    public function create()
    {
        return view('frontend.customer.requests.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'page_name' => ['required','string','max:255'],
            'social_media' => ['required','string','max:50'],
            'ads_budget_bdt' => ['required','numeric','min:0'],
            'days' => ['required','integer','min:1'],
            'post_link' => ['nullable','url','max:2048'],
        ]);

        CustomerRequest::create([
            'user_id' => $request->user()->id,
            // keep legacy title filled with page_name for compatibility
            'title' => $validated['page_name'],
            'description' => null,
            'page_name' => $validated['page_name'],
            'social_media' => $validated['social_media'],
            'ads_budget_bdt' => $validated['ads_budget_bdt'],
            'days' => $validated['days'],
            'post_link' => $validated['post_link'] ?? null,
            'status' => CustomerRequest::STATUS_PENDING,
        ]);

        return redirect()->route('customer.requests.index')->with('success', 'Request submitted successfully.');
    }

    public function show(Request $request, CustomerRequest $customerRequest)
    {
        if ($customerRequest->user_id !== $request->user()->id) abort(403);
        return view('frontend.customer.requests.show', compact('customerRequest'));
    }

    public function edit(Request $request, CustomerRequest $customerRequest)
    {
        if ($customerRequest->user_id !== $request->user()->id) abort(403);
        return view('frontend.customer.requests.edit', compact('customerRequest'));
    }

    public function update(Request $request, CustomerRequest $customerRequest)
    {
        if ($customerRequest->user_id !== $request->user()->id) abort(403);
        $validated = $request->validate([
            'page_name' => ['required','string','max:255'],
            'social_media' => ['required','string','max:50'],
            'ads_budget_bdt' => ['required','numeric','min:0'],
            'days' => ['required','integer','min:1'],
            'post_link' => ['nullable','url','max:2048'],
        ]);
        $customerRequest->update([
            'title' => $validated['page_name'],
            'description' => null,
            'page_name' => $validated['page_name'],
            'social_media' => $validated['social_media'],
            'ads_budget_bdt' => $validated['ads_budget_bdt'],
            'days' => $validated['days'],
            'post_link' => $validated['post_link'] ?? null,
        ]);
        return redirect()->route('customer.requests.index')->with('success', 'Request updated.');
    }

    public function destroy(Request $request, CustomerRequest $customerRequest)
    {
        if ($customerRequest->user_id !== $request->user()->id) abort(403);
        $customerRequest->delete();
        return redirect()->route('customer.requests.index')->with('success', 'Request deleted.');
    }
}
