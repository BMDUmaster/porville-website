<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class AccountNotificationService
{
    /**
     * Welcome the customer after signup (in-app + email).
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

        self::sendAfterResponse($user->email, $subject, implode("\n", [
            "Hello {$user->name},",
            '',
            $message,
            '',
            'Login Email: ' . $user->email,
            'Shop now: ' . route('frontend.products'),
            '',
            'Warm Regards,',
            'Porville Team',
            config('app.url'),
        ]));
    }

    /**
     * Security email whenever the customer signs in with their password.
     */
    public static function notifyLoggedIn(User $user, Request $request): void
    {
        $time = now()->timezone(config('app.timezone', 'Asia/Kolkata'))->format('d M Y, h:i A');

        self::sendAfterResponse($user->email, 'New login to your Porville account', implode("\n", [
            "Hello {$user->name},",
            '',
            'Your Porville account was just signed in.',
            '',
            'Time: ' . $time,
            'IP Address: ' . ($request->ip() ?? 'Unknown'),
            'Device: ' . ($request->userAgent() ?: 'Unknown'),
            '',
            'If this was you, no action is needed.',
            'If you do not recognise this login, please reset your password right away: ' . route('frontend.password.forgot'),
            '',
            'Warm Regards,',
            'Porville Team',
            config('app.url'),
        ]));
    }

    /**
     * Send after the response so SMTP latency never slows down login/signup.
     */
    private static function sendAfterResponse(?string $email, string $subject, string $body): void
    {
        if (! $email) {
            return;
        }

        dispatch(function () use ($email, $subject, $body) {
            try {
                Mail::raw($body, function ($mail) use ($email, $subject) {
                    $mail->to($email)->subject($subject);
                });
            } catch (Throwable $e) {
                Log::error('Account email "' . $subject . '" failed for ' . $email . ': ' . $e->getMessage());
            }
        })->afterResponse();
    }
}
