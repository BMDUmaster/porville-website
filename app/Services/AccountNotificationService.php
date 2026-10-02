<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use App\Support\AdminModules;
use App\Support\PorvilleMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class AccountNotificationService
{
    /**
     * Welcome the customer after signup (in-app + email) and tell the admins.
     */
    public static function notifyRegistered(User $user): void
    {
        $subject = 'Welcome to Porville, ' . $user->name . '!';
        $message = 'Your Porville account has been created successfully. Explore fresh cuts, track your orders and get exclusive offers.';

        try {
            Notification::create([
                'recipient_id' => $user->id,
                'subject'      => $subject,
                'message'      => $message,
                'sent_by'      => null,
            ]);
        } catch (Throwable $e) {
            Log::error('Welcome in-app notification failed for user ' . $user->id . ': ' . $e->getMessage());
        }

        PorvilleMail::sendAfterResponse($user->email, $subject, 'emails.message', [
            'heading'      => 'Welcome to Porville!',
            'greetingName' => $user->name,
            'lines'        => [$message],
            'details'      => ['Login Email' => $user->email],
            'buttonText'   => 'Start Shopping',
            'buttonUrl'    => route('frontend.products'),
        ]);

        PorvilleMail::sendAfterResponse(AdminModules::recipients('customers'), "New customer registered: {$user->name}", 'emails.message', [
            'heading'    => 'New Customer Registered',
            'lines'      => ['A new customer just created an account on Porville.'],
            'details'    => [
                'Name'       => $user->name,
                'Email'      => $user->email,
                'Phone'      => $user->phone ?: '—',
                'Registered' => now()->format('d M Y, h:i A'),
            ],
            'buttonText' => 'View Customer',
            'buttonUrl'  => route('dashboard.users.show', $user),
        ]);
    }

    /**
     * Security email whenever the customer signs in with their password.
     */
    public static function notifyLoggedIn(User $user, Request $request): void
    {
        PorvilleMail::sendAfterResponse($user->email, 'New login to your Porville account', 'emails.login-alert', [
            'name'     => $user->name,
            'time'     => now()->timezone(config('app.timezone', 'Asia/Kolkata'))->format('d M Y, h:i A'),
            'ip'       => $request->ip() ?? 'Unknown',
            'device'   => self::deviceLabel($request->userAgent()),
            'resetUrl' => route('frontend.password.forgot'),
        ]);
    }

    /**
     * "Chrome on Windows" style label from a user agent string.
     */
    private static function deviceLabel(?string $userAgent): string
    {
        $ua = (string) $userAgent;

        if ($ua === '') {
            return 'Unknown device';
        }

        $browser = match (true) {
            str_contains($ua, 'Edg/')                                => 'Edge',
            str_contains($ua, 'OPR/') || str_contains($ua, 'Opera')  => 'Opera',
            str_contains($ua, 'SamsungBrowser')                      => 'Samsung Internet',
            str_contains($ua, 'Chrome/')                             => 'Chrome',
            str_contains($ua, 'Firefox/')                            => 'Firefox',
            str_contains($ua, 'Safari/')                             => 'Safari',
            default                                                  => 'Browser',
        };

        $os = match (true) {
            str_contains($ua, 'Windows')                             => 'Windows',
            str_contains($ua, 'Android')                             => 'Android',
            str_contains($ua, 'iPhone') || str_contains($ua, 'iPad') => 'iOS',
            str_contains($ua, 'Mac OS')                              => 'macOS',
            str_contains($ua, 'Linux')                               => 'Linux',
            default                                                  => 'unknown system',
        };

        return "{$browser} on {$os}";
    }
}
