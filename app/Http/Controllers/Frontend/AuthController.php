<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /** GET /account/login */
    public function showLogin()
    {
        if (Auth::guard('web_frontend')->check()) {
            return redirect()->route('frontend.profile');
        }
        return view('frontend.login');
    }

    /** POST /account/login */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::guard('web_frontend')->attempt($credentials, $request->boolean('remember'))) {
            $user = Auth::guard('web_frontend')->user();

            if ($user && in_array($user->status, ['blocked', 'inactive'], true)) {
                Auth::guard('web_frontend')->logout();

                return back()->withErrors([
                    'email' => 'Your account has been blocked. Please contact support.',
                ])->onlyInput('email');
            }

            $request->session()->regenerate();
            return redirect()->intended(route('frontend.profile'));
        }

        return back()->withErrors(['email' => 'Invalid email or password.'])->onlyInput('email');
    }

    /** GET /account/register */
    public function showRegister()
    {
        if (Auth::guard('web_frontend')->check()) {
            return redirect()->route('frontend.profile');
        }
        return view('frontend.signup');
    }

    /** POST /account/register */
    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'phone'    => 'nullable|string|max:20',
            'terms'    => 'accepted',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'phone'    => $request->phone ?? null,
            'role'     => 'customer',
            'status'   => 'active',
        ]);

        Auth::guard('web_frontend')->login($user);
        $request->session()->regenerate();

        return redirect()->route('frontend.profile')->with('success', 'Account created successfully! Welcome to FarmSea.');
    }
    /** POST /account/logout */
    public function logout(Request $request)
    {
        Auth::guard('web_frontend')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('frontend.login');
    }
}
