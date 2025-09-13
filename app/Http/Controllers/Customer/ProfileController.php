<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    /**
     * Display the customer's profile.
     */
    public function show()
    {
        $user = Auth::user();
        return view('frontend.customer.profile.show', compact('user'));
    }

    /**
     * Update the customer's profile information.
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:500'],
        ]);

        $user->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        return redirect()->route('customer.profile.show')
            ->with('success', 'Profile updated successfully!');
    }

    /**
     * Update the customer's password.
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        Auth::user()->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('customer.profile.show')
            ->with('success', 'Password updated successfully!');
    }

    /**
     * Update the customer's profile image.
     */
    public function updateImage(Request $request)
    {
        $request->validate([
            'profile_image' => ['required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        $user = Auth::user();
        
        // Delete old image if exists
        if ($user->profile_image) {
            Storage::disk('public')->delete($user->profile_image);
        }

        // Store new image
        $imagePath = $request->file('profile_image')->store('profile-images', 'public');
        
        $user->update([
            'profile_image' => $imagePath,
        ]);

        return redirect()->route('customer.profile.show')
            ->with('success', 'Profile image updated successfully!');
    }

    /**
     * Delete the customer's profile image.
     */
    public function deleteImage()
    {
        $user = Auth::user();
        
        if ($user->profile_image) {
            Storage::disk('public')->delete($user->profile_image);
            $user->update(['profile_image' => null]);
        }

        return redirect()->route('customer.profile.show')
            ->with('success', 'Profile image deleted successfully!');
    }
}