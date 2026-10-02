<?php

namespace App\Support;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

/**
 * Sends the branded HTML emails in resources/views/emails. A failed send is
 * logged and never breaks the page that triggered it.
 */
class PorvilleMail
{
    public static function send(string|array $to, string $subject, string $view, array $data = []): bool
    {
        $recipients = array_values(array_filter((array) $to));

        if (! $recipients) {
            return false;
        }

        try {
            Mail::send($view, $data, function ($mail) use ($recipients, $subject) {
                $mail->to(array_shift($recipients))->subject($subject);

                // Several admins: one email, the rest in BCC so addresses stay private.
                foreach ($recipients as $extra) {
                    $mail->bcc($extra);
                }
            });

            return true;
        } catch (Throwable $e) {
            Log::error("Email \"{$subject}\" failed: " . $e->getMessage());

            return false;
        }
    }

    /**
     * Same as send(), but after the response so SMTP never slows the page.
     */
    public static function sendAfterResponse(string|array $to, string $subject, string $view, array $data = []): void
    {
        dispatch(fn () => self::send($to, $subject, $view, $data))->afterResponse();
    }
}
