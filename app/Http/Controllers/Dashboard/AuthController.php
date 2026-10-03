<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Support\AdminModules;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check() && Auth::user()->isStaff()) {
            return redirect()->to(AdminModules::homeUrl(Auth::user()));
        }
        return view('dashboard.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            if (! Auth::user()->isStaff()) {
                Auth::logout();
                return back()->withErrors(['email' => 'Access denied. Admin account required.']);
            }

            if (Auth::user()->status !== 'active') {
                Auth::logout();
                return back()->withErrors(['email' => 'This admin account is disabled. Contact the main admin.']);
            }

            // Sub admins without the Dashboard module land on their first module.
            return redirect()->intended(AdminModules::homeUrl(Auth::user()));
        }

        return back()->withErrors(['email' => 'Invalid credentials.'])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('dashboard.login');
    }
}
