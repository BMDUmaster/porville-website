<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /** GET /account/profile */
    public function index()
    {
        $user   = Auth::guard('web_frontend')->user();
        $orders = $user->orders()->with('items.product')->latest()->take(5)->get();
        return view('frontend.profile', compact('user', 'orders'));
    }

    /** PUT /account/profile */
    public function update(Request $request)
    {
        $user = Auth::guard('web_frontend')->user();

        $data = $request->validate([
            'name'  => 'required|string|max:100',
            'phone' => 'nullable|string|max:20',
        ]);

        $user->update($data);

        return back()->with('success', 'Profile updated successfully.');
    }

    /** PUT /account/password */
    public function updatePassword(Request $request)
    {
        $user = Auth::guard('web_frontend')->user();

        $request->validate([
            'current_password' => 'required',
            'password'         => 'required|string|min:6|confirmed',
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        $user->update(['password' => Hash::make($request->password)]);

        return back()->with('success', 'Password updated successfully.');
    }
}
