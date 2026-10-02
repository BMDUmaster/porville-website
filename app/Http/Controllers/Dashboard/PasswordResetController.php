<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

/**
 * Admin / sub admin password reset with an emailed one-time code.
 */
class PasswordResetController extends Controller
{
    private const OTP_TTL_MINUTES = 10;
    private const MAX_ATTEMPTS = 5;

    public function show(Request $request)
    {
        $email = $request->session()->get('admin_reset_email')
            ?? (Auth::check() && Auth::user()->isStaff() ? Auth::user()->email : null);

        return view('dashboard.auth.forgot-password', [
            'email'   => $email,
            'otpSent' => $request->session()->has('admin_reset_hash') || $request->session()->has('admin_reset_unknown'),
        ]);
    }

    public function sendOtp(Request $request)
    {
        $data = $request->validate(['email' => ['required', 'email']]);
        $email = strtolower(trim($data['email']));

        $user = User::query()
            ->whereRaw('LOWER(email) = ?', [$email])
            ->whereIn('role', ['admin', 'sub_admin'])
            ->where('status', 'active')
            ->first();

        // Same answer either way, so the form does not reveal which emails are admins.
        if ($user) {
            $otp = (string) random_int(100000, 999999);

            try {
                Mail::send('emails.otp', [
                    'title'   => 'Admin Password Reset',
                    'intro'   => 'We received a request to reset the password of your Porville admin account.',
                    'otp'     => $otp,
                    'minutes' => self::OTP_TTL_MINUTES,
                ], fn ($mail) => $mail->to($user->email)->subject('Porville Admin Password Reset Code'));
            } catch (Throwable $e) {
                Log::error('Admin reset OTP email failed: ' . $e->getMessage());

                return back()->withInput()->withErrors(['email' => 'Could not send the email right now. Please try again in a minute.']);
            }

            $request->session()->put([
                'admin_reset_email'   => $user->email,
                'admin_reset_hash'    => Hash::make($otp),
                'admin_reset_expires' => now()->addMinutes(self::OTP_TTL_MINUTES)->timestamp,
                'admin_reset_tries'   => 0,
            ]);
        } else {
            $request->session()->put('admin_reset_email', $email);
            $request->session()->forget(['admin_reset_hash', 'admin_reset_expires']);
            $request->session()->put('admin_reset_unknown', true);
        }

        return redirect()->route('dashboard.password.forgot')
            ->with('success', 'If this email belongs to an active admin account, a 6-digit code has been sent to it.');
    }

    public function reset(Request $request)
    {
        $data = $request->validate([
            'otp'      => ['required', 'digits:6'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $session = $request->session();
        $email = $session->get('admin_reset_email');
        $hash = $session->get('admin_reset_hash');
        $tries = (int) $session->get('admin_reset_tries', 0) + 1;
        $session->put('admin_reset_tries', $tries);

        if (! $email || ! $hash || now()->timestamp > (int) $session->get('admin_reset_expires') || $tries > self::MAX_ATTEMPTS) {
            $session->forget(['admin_reset_hash', 'admin_reset_expires', 'admin_reset_tries']);

            return redirect()->route('dashboard.password.forgot')
                ->withErrors(['otp' => 'This code has expired. Please request a new one.']);
        }

        if (! Hash::check($data['otp'], $hash)) {
            return back()->withErrors(['otp' => 'Invalid code. Please check the email and try again.']);
        }

        $user = User::where('email', $email)->whereIn('role', ['admin', 'sub_admin'])->first();

        if (! $user) {
            return redirect()->route('dashboard.password.forgot')->withErrors(['otp' => 'Account not found.']);
        }

        $user->forceFill(['password' => Hash::make($data['password'])])->save();
        $session->forget(['admin_reset_email', 'admin_reset_hash', 'admin_reset_expires', 'admin_reset_tries', 'admin_reset_unknown']);

        // Signed in already (came from Profile)? Keep the session; otherwise go log in.
        if (Auth::check() && Auth::id() === $user->id) {
            return redirect()->route('dashboard.profile')->with('success', 'Password changed successfully.');
        }

        return redirect()->route('dashboard.login')->with('success', 'Password changed. Please log in with your new password.');
    }

    public function restart(Request $request)
    {
        $request->session()->forget(['admin_reset_email', 'admin_reset_hash', 'admin_reset_expires', 'admin_reset_tries', 'admin_reset_unknown']);

        return redirect()->route('dashboard.password.forgot');
    }
}
