<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\Order;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Throwable;

class OrderStatusNotificationService
{
    /**
     * Notify user (in-app + email) when order status changes or a new order is placed.
     */
    public static function notifyStatusChange(Order $order, ?string $oldStatus = null): void
    {
        $order->loadMissing(['user', 'deliveryBoy', 'items.product']);
        $newStatus = $order->status;

        // Do not notify if status has not changed
        if ($oldStatus !== null && $oldStatus === $newStatus) {
            return;
        }

        $orderNumber = $order->order_number ?: ('#' . $order->id);
        [$subject, $message, $emailBody] = self::buildMessageAndEmail($order, $orderNumber, $newStatus);

        // 1. Save In-App Notification (database)
        if ($order->user_id) {
            try {
                $sentBy = auth('web')->id() ?? auth('web_frontend')->id() ?? null;

                Notification::create([
                    'recipient_id' => $order->user_id,
                    'subject'      => $subject,
                    'message'      => $message,
                    'sent_by'      => $sentBy,
                ]);
            } catch (Throwable $e) {
                Log::error('In-app notification creation failed: ' . $e->getMessage());
            }
        }

        // 2. Send Email Notification to user
        $recipientEmail = $order->shipping_address['email'] ?? $order->user?->email;

        if ($recipientEmail) {
            try {
                Mail::raw($emailBody, function ($mail) use ($recipientEmail, $subject) {
                    $mail->to($recipientEmail)->subject($subject);
                });
            } catch (Throwable $e) {
                Log::error('Order status email notification failed: ' . $e->getMessage());
            }
        }
    }

    private static function buildMessageAndEmail(Order $order, string $orderNumber, string $status): array
    {
        $userName = $order->user?->name ?? $order->shipping_address['name'] ?? 'Customer';
        $totalFormatted = 'Rs' . number_format($order->total, 2);

        switch ($status) {
            case 'confirmed':
                $subject = "Order {$orderNumber} Confirmed! - Porville";
                $message = "Your order {$orderNumber} of {$totalFormatted} has been confirmed. We are getting your fresh items ready.";
                break;

            case 'processing':
                $subject = "Order {$orderNumber} is Processing - Porville";
                $message = "Great news! Your fresh cuts for order {$orderNumber} are being hygienically prepared & cold-chain packed.";
                break;

            case 'out_for_delivery':
                $deliveryPartner = $order->deliveryBoy?->partner_name ?? 'Our delivery executive';
                $deliveryPhone = $order->deliveryBoy?->phone ?? '';
                $partnerInfo = $deliveryPhone ? "{$deliveryPartner} ({$deliveryPhone})" : $deliveryPartner;

                $subject = "Order {$orderNumber} Out for Delivery! 🚚 - Porville";
                $message = "Your order {$orderNumber} is out for delivery with {$partnerInfo}. Please be ready to receive your fresh package!";
                break;

            case 'delivered':
                $subject = "Order {$orderNumber} Delivered Successfully! 🎉 - Porville";
                $message = "Your order {$orderNumber} of {$totalFormatted} has been delivered. Thank you for choosing Porville! Enjoy your fresh meal.";
                break;

            case 'cancelled':
                $subject = "Order {$orderNumber} Cancelled - Porville";
                $message = "Your order {$orderNumber} has been cancelled. If you have any questions, please contact Porville support.";
                break;

            case 'pending':
            default:
                $subject = "Order {$orderNumber} Placed Successfully - Porville";
                $message = "Thank you for your order {$orderNumber} of {$totalFormatted}! We have received your order and will process it shortly.";
                break;
        }

        $addressText = trim(
            ($order->shipping_address['address'] ?? '') . ', ' .
            ($order->shipping_address['sector'] ?? '') . ', ' .
            ($order->shipping_address['city'] ?? '') . ' - ' .
            ($order->shipping_address['pincode'] ?? '')
        );

        $emailBody = implode("\n", [
            "Hello {$userName},",
            "",
            $message,
            "",
            "--- Order Summary ---",
            "Order Number: {$orderNumber}",
            "Current Status: {$order->status_label}",
            "Total Amount: {$totalFormatted}",
            "Payment Method: " . strtoupper($order->payment_method ?? 'COD'),
            "Delivery Slot: " . ($order->delivery_slot ?? 'Standard Slot'),
            "",
            "Delivery Address:",
            $addressText,
            ...($status === 'delivered' ? [
                "",
                "We would love your feedback! Review your order here:",
                URL::temporarySignedRoute('frontend.review.create', now()->addDays(30), ['order' => $order->id]),
                "This private review link is valid for 30 days.",
            ] : []),
            "",
            "If you have any questions about your order, please contact Porville support.",
            "",
            "Warm Regards,",
            "Porville Team",
            config('app.url')
        ]);

        return [$subject, $message, $emailBody];
    }
}
