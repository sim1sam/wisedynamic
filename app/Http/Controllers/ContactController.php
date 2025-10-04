<?php

namespace App\Http\Controllers;

use App\Models\CustomerMessage;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    /**
     * Store a new contact form submission
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function submitForm(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $message = CustomerMessage::create($validated);
        
        // Create notification for admin
        \App\Models\Notification::createNotification(
            'message',
            'New Contact Message',
            "New message from {$validated['name']} - {$validated['subject']}",
            route('admin.messages.show', $message->id),
            $message->id,
            'App\\Models\\CustomerMessage'
        );

        return redirect()->route('contact')->with('success', 'Your message has been sent successfully!');
    }

    /**
     * Display a listing of contact messages in admin panel
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Use client-side DataTables pagination; return full collection
        $messages = CustomerMessage::orderBy('created_at', 'desc')->get();
        return view('admin.messages.index', compact('messages'));
    }

    /**
     * Display the specified message
     *
     * @param  \App\Models\CustomerMessage  $message
     * @return \Illuminate\View\View
     */
    public function show(CustomerMessage $message)
    {
        if (!$message->is_read) {
            $message->update(['is_read' => true]);
        }
        
        return view('admin.messages.show', compact('message'));
    }

    /**
     * Remove the specified message
     *
     * @param  \App\Models\CustomerMessage  $message
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(CustomerMessage $message)
    {
        $message->delete();
        return redirect()->route('admin.messages.index')->with('success', 'Message deleted successfully!');
    }
}
