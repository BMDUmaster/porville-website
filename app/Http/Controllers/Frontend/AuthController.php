<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use RuntimeException;
use Illuminate\Validation\ValidationException;
use Throwable;

class AuthController extends Controller
{
    private const REGISTER_OTP_TTL_MINUTES = 10;
    private const REGISTER_DEFAULT_OTP = '1111';
    private const PASSWORD_OTP_TTL_MINUTES = 10;

    /** GET /account/login */
    public function showLogin()
    {
        if (Auth::guard('web_frontend')->check()) {
            return redirect()->route('frontend.profile');
        }
        return view('frontend.login');
    }

    /** POST /account/check-email */
    public function checkEmail(Request $request)
    {
        $data = $request->validate(['email' => ['required', 'email']]);
        $email = strtolower(trim($data['email']));

        return response()->json([
            'exists' => User::query()->where('email', $email)->exists(),
            'email' => $email,
        ]);
    }

    /** POST /account/login */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        // Keep customers signed in across normal session expiry; logout remains manual.
        if (Auth::guard('web_frontend')->attempt($credentials, true)) {
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

    /** GET /account/forgot-password */
    public function showForgotPassword(Request $request)
    {
        if (Auth::guard('web_frontend')->check()) {
            return redirect()->route('frontend.profile');
        }

        if ($request->boolean('edit-email')) {
            session()->forget([
                'password_reset_otp_email',
                'password_reset_otp_hash',
                'password_reset_otp_expires_at',
                'password_reset_verified',
            ]);
        }

        $step = session('password_reset_verified')
            ? 'reset'
            : (session('password_reset_otp_email') ? 'otp' : 'email');

        return view('frontend.forgot-password', compact('step'));
    }

    /** POST /account/forgot-password/send-otp */
    public function sendForgotPasswordOtp(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ]);

        $user = User::where('email', $data['email'])->firstOrFail();
        $otp = $this->generateOtp();

        try {
            $this->sendOtpEmail($user->email, $otp, 'password reset', self::PASSWORD_OTP_TTL_MINUTES);
        } catch (Throwable $exception) {
            report($exception);

            return back()
                ->withErrors(['email' => 'Could not send OTP email. Please check SMTP settings and try again.'])
                ->onlyInput('email');
        }

        session([
            'password_reset_otp_email' => $user->email,
            'password_reset_otp_hash' => Hash::make($otp),
            'password_reset_otp_expires_at' => now()->addMinutes(self::PASSWORD_OTP_TTL_MINUTES)->timestamp,
        ]);
        session()->forget('password_reset_verified');

        return redirect()
            ->route('frontend.password.forgot')
            ->with('success', 'OTP sent successfully. Please check your email.');
    }

    /** POST /account/forgot-password/verify-otp */
    public function verifyForgotPasswordOtp(Request $request)
    {
        $request->validate([
            'email_otp' => ['required', 'digits:4'],
        ]);

        $otpHash = session('password_reset_otp_hash');
        $expiresAt = (int) session('password_reset_otp_expires_at', 0);

        if (! $otpHash || $expiresAt < now()->timestamp || ! Hash::check($request->email_otp, $otpHash)) {
            throw ValidationException::withMessages([
                'email_otp' => 'Invalid or expired OTP.',
            ]);
        }

        session(['password_reset_verified' => true]);

        return redirect()
            ->route('frontend.password.forgot')
            ->with('success', 'OTP verified. Create your new password.');
    }

    /** POST /account/forgot-password/reset */
    public function resetForgotPassword(Request $request)
    {
        $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $email = session('password_reset_otp_email');

        if (! $email || ! session('password_reset_verified')) {
            throw ValidationException::withMessages([
                'email' => 'Please verify your email OTP first.',
            ]);
        }

        $user = User::where('email', $email)->firstOrFail();
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        session()->forget([
            'password_reset_otp_email',
            'password_reset_otp_hash',
            'password_reset_otp_expires_at',
            'password_reset_verified',
        ]);

        return redirect()
            ->route('frontend.login')
            ->with('success', 'Password created successfully. Please log in with your new password.');
    }

    /** POST /account/register/verify-otp (AJAX live check) */
    public function verifyRegisterOtp(Request $request)
    {
        $request->merge(['email' => strtolower(trim((string) $request->input('email')))]);

        $request->validate([
            'email' => $this->registrationEmailRules(),
            'otp'   => ['required', 'digits:4'],
        ]);

        $email     = strtolower($request->email);
        $otpHash   = session('register_otp_hash');
        $otpEmail  = session('register_otp_email');
        $expiresAt = (int) session('register_otp_expires_at', 0);

        $valid = $otpEmail === $email
            && $otpHash
            && $expiresAt >= now()->timestamp
            && Hash::check($request->otp, $otpHash);

        return response()->json([
            'valid'   => $valid,
            'message' => $valid ? 'OTP verified successfully!' : 'Invalid or expired OTP.',
        ]);
    }

    /** POST /account/register/send-otp */
    public function sendRegisterOtp(Request $request)
    {
        $request->merge(['email' => strtolower(trim((string) $request->input('email')))]);

        $data = $request->validate([
            'email' => $this->registrationEmailRules(),
        ]);

        $email = strtolower($data['email']);

        if (User::query()->where('email', $email)->exists()) {
            return response()->json([
                'success' => false,
                'email_exists' => true,
                'message' => 'This email is already registered. Please log in or reset your password.',
            ], 422);
        }

        $otp = $this->generateOtp();

        try {
            $this->sendOtpEmail($email, $otp, 'registration', self::REGISTER_OTP_TTL_MINUTES);
        } catch (Throwable $exception) {
            report($exception);

            $errorMsg = 'Could not send OTP email. ' . $exception->getMessage();

            return response()->json([
                'success' => false,
                'message' => $errorMsg,
            ], 422);
        }

        session([
            'register_otp_email' => $email,
            'register_otp_hash' => Hash::make($otp),
            'register_otp_expires_at' => now()->addMinutes(self::REGISTER_OTP_TTL_MINUTES)->timestamp,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'OTP sent successfully. Please check your email inbox.',
        ]);
    }

    public function register(Request $request)
    {
        $request->merge([
            'name' => trim((string) $request->input('name')),
            'email' => strtolower(trim((string) $request->input('email'))),
            'password' => trim((string) $request->input('password')),
        ]);

        $request->validate([
            'name'      => ['bail', 'required', 'string', 'min:3', 'max:50', 'regex:/^[A-Za-z]+(?:\\s[A-Za-z]+)*$/'],
            'email'     => [...$this->registrationEmailRules(), 'unique:users,email'],
            'email_otp' => ['required', 'digits:4'],
            'password'  => ['bail', 'required', 'string', 'min:8', 'regex:/[A-Z]/', 'regex:/[a-z]/', 'regex:/[0-9]/', 'regex:/[^A-Za-z0-9\\s]/', 'not_regex:/\\s/', 'not_regex:/(password|123456|qwerty)/i'],
        ], [
            'name.min' => 'Full name must be at least 3 characters.',
            'name.max' => 'Full name must not exceed 50 characters.',
            'name.regex' => 'Full name may contain letters and single spaces only.',
            'email.regex' => 'Enter a valid email address, such as rahul.sharma@gmail.com.',
            'email.unique' => 'This email is already registered. Please log in or reset your password.',
            'password.min' => 'Password must be at least 8 characters.',
            'password.regex' => 'Password must include uppercase, lowercase, number, and special character.',
            'password.not_regex' => 'Password cannot contain spaces or common passwords.',
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

    private function generateOtp(): string
    {
        return (string) random_int(1000, 9999);
    }

    private function registrationEmailRules(): array
    {
        return [
            'bail',
            'required',
            'string',
            'max:254',
            'regex:/^(?!.*\\.\\.)[A-Za-z0-9](?:[A-Za-z0-9._-]*[A-Za-z0-9])?@[A-Za-z0-9](?:[A-Za-z0-9-]*[A-Za-z0-9])?(?:\\.[A-Za-z0-9](?:[A-Za-z0-9-]*[A-Za-z0-9])?)*\\.[A-Za-z]{2,}$/',
        ];
    }

    private function sendOtpEmail(string $email, string $otp, string $purpose, int $ttlMinutes): void
    {
        $this->ensureMailIsConfigured();

        $subject = 'FarmSea OTP for ' . ucwords($purpose);
        $body = implode("\n", [
            'Hello,',
            '',
            "Your FarmSea OTP for {$purpose} is: {$otp}",
            '',
            "This OTP is valid for {$ttlMinutes} minutes.",
            'If you did not request this, please ignore this email.',
            '',
            'Regards,',
            'FarmSea Team',
        ]);

        Mail::raw($body, function ($message) use ($email, $subject) {
            $message->to($email)->subject($subject);
        });
    }

    private function ensureMailIsConfigured(): void
    {
        $driver = config('mail.default');

        if ($driver === 'log') {
            return;
        }

        if ($driver !== 'smtp') {
            throw new RuntimeException('OTP email requires MAIL_MAILER=smtp or MAIL_MAILER=log.');
        }

        $smtp = config('mail.mailers.smtp', []);
        $required = ['host', 'port', 'username', 'password'];

        foreach ($required as $key) {
            $value = $smtp[$key] ?? null;

            if ($value === null || $value === '' || $value === 'null') {
                throw new RuntimeException("Missing SMTP setting in .env: MAIL_" . strtoupper($key) . '. Please configure your email credentials.');
            }
        }
    }
}
