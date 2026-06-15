<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    private const REGISTER_OTP_TTL_MINUTES = 10;
    private const REGISTER_DEFAULT_OTP = '1111';

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
    public function sendRegisterOtp(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'email', 'unique:users,email'],
        ]);

        $otp = self::REGISTER_DEFAULT_OTP;
        $email = strtolower($data['email']);

        session([
            'register_otp_email' => $email,
            'register_otp_hash' => Hash::make($otp),
            'register_otp_expires_at' => now()->addMinutes(self::REGISTER_OTP_TTL_MINUTES)->timestamp,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Use OTP 1111 to continue.',
        ]);
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|unique:users,email',
            'email_otp' => ['required', 'digits:4'],
            'password' => 'required|string|min:8',
        ]);

        $email = strtolower($request->email);
        $otpEmail = session('register_otp_email');
        $otpHash = session('register_otp_hash');
        $expiresAt = (int) session('register_otp_expires_at', 0);

        if (
            $otpEmail !== $email
            || ! $otpHash
            || $expiresAt < now()->timestamp
            || ! Hash::check($request->email_otp, $otpHash)
        ) {
            throw ValidationException::withMessages([
                'email_otp' => 'Invalid or expired OTP.',
            ]);
        }

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'customer',
            'status'   => 'active',
        ]);

        session()->forget(['register_otp_email', 'register_otp_hash', 'register_otp_expires_at']);

        Auth::guard('web_frontend')->login($user);
        $request->session()->regenerate();

        return redirect()->intended(route('frontend.profile'))->with('success', 'Account created successfully! Welcome to FarmSea.');
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
