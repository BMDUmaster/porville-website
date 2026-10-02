<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\Order;
use App\Support\AdminModules;
use App\Support\PorvilleMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Throwable;

class OrderStatusNotificationService
{
    /**
     * Notify the customer (in-app + email) and the admins (email) when an
     * order is placed or its status changes.
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
        $content = self::content($order, $orderNumber, $newStatus);

        // 1. In-app notification for the customer
        if ($order->user_id) {
            try {
                $sentBy = auth('web')->id() ?? auth('web_frontend')->id() ?? null;

                Notification::create([
                    'recipient_id' => $order->user_id,
                    'subject'      => $content['subject'],
                    'message'      => $content['message'],
                    'sent_by'      => $sentBy,
                ]);
            } catch (Throwable $e) {
                Log::error('In-app notification creation failed: ' . $e->getMessage());
            }
        }

        // 2. Email to the customer
        $recipientEmail = $order->shipping_address['email'] ?? $order->user?->email;
        $customerName = $order->user?->name ?? $order->shipping_address['name'] ?? 'Customer';

        PorvilleMail::sendAfterResponse($recipientEmail, $content['subject'], 'emails.order', [
            'order'           => $order,
            'heading'         => $content['heading'],
            'intro'           => $content['intro'],
            'icon'            => $content['icon'],
            'greetingName'    => $customerName,
            'messageText'     => $content['message'],
            'deliveryPartner' => $content['partner'],
            'buttonText'      => 'View My Order',
            'buttonUrl'       => $order->user_id ? route('frontend.order.show', $order->id) : route('frontend.track'),
            'reviewUrl'       => $newStatus === 'delivered'
                ? URL::temporarySignedRoute('frontend.review.create', now()->addDays(30), ['order' => $order->id])
                : null,
        ]);

        // 3. Email to the admins who handle orders
        $isNew = $oldStatus === null && $newStatus === 'pending';
        $total = '₹' . number_format((float) $order->total, 2);

        PorvilleMail::sendAfterResponse(
            AdminModules::recipients('orders'),
            $isNew ? "New order {$orderNumber} ({$total}) - Porville" : "Order {$orderNumber} is now {$order->status_label} - Porville",
            'emails.order',
            [
                'order'           => $order,
                'heading'         => $isNew ? 'New Order Received' : 'Order Status Updated',
                'intro'           => $isNew
                    ? "{$customerName} just placed an order on Porville."
                    : "Order {$orderNumber} moved" . ($oldStatus ? ' from ' . ucwords(str_replace('_', ' ', $oldStatus)) : '') . " to {$order->status_label}.",
                'icon'            => $isNew ? '🛒' : $content['icon'],
                'greetingName'    => 'Team',
                'messageText'     => $isNew
                    ? "Order {$orderNumber} of {$total} is waiting to be confirmed."
                    : "The customer has been notified about this update.",
                'deliveryPartner' => $content['partner'],
                'buttonText'      => 'Open in Admin',
                'buttonUrl'       => route('dashboard.orders.show', $order),
            ]
        );
    }

    private static function content(Order $order, string $orderNumber, string $status): array
    {
        $total = '₹' . number_format((float) $order->total, 2);
        $partner = null;

        switch ($status) {
            case 'confirmed':
                $subject = "Order {$orderNumber} Confirmed! - Porville";
                $heading = 'Order Confirmed!';
                $intro = 'Good news — your order is confirmed and our team is getting it ready.';
                $icon = '✓';
                $message = "Your order {$orderNumber} of {$total} has been confirmed. We are getting your fresh items ready.";
                break;

            case 'processing':
                $subject = "Order {$orderNumber} is Processing - Porville";
                $heading = 'Your Order Is Being Prepared';
                $intro = 'Your fresh cuts are being hygienically prepared and cold-chain packed.';
                $icon = '⏳';
                $message = "Great news! Your fresh cuts for order {$orderNumber} are being hygienically prepared & cold-chain packed.";
                break;

            case 'out_for_delivery':
                $partnerName = $order->deliveryBoy?->partner_name ?? 'Our delivery executive';
                $partnerPhone = $order->deliveryBoy?->phone ?? '';
                $partner = $partnerPhone ? "{$partnerName} ({$partnerPhone})" : $partnerName;

                $subject = "Order {$orderNumber} Out for Delivery! 🚚 - Porville";
                $heading = 'Out for Delivery!';
                $intro = 'Your order is on its way. Please be ready to receive your fresh package.';
                $icon = '🚚';
                $message = "Your order {$orderNumber} is out for delivery with {$partner}. Please be ready to receive your fresh package!";
                break;

            case 'delivered':
                $subject = "Order {$orderNumber} Delivered Successfully! 🎉 - Porville";
                $heading = 'Order Delivered!';
                $intro = 'Your order has been delivered. Enjoy your fresh meal!';
                $icon = '🎉';
                $message = "Your order {$orderNumber} of {$total} has been delivered. Thank you for choosing Porville! Enjoy your fresh meal.";
                break;

            case 'cancelled':
                $subject = "Order {$orderNumber} Cancelled - Porville";
                $heading = 'Order Cancelled';
                $intro = 'Your order has been cancelled.';
                $icon = '✕';
                $message = "Your order {$orderNumber} has been cancelled. If you have any questions, please contact Porville support.";
                break;

            case 'pending':
            default:
                $subject = "Order {$orderNumber} Placed Successfully - Porville";
                $heading = 'Order Placed Successfully!';
                $intro = "Thank you for choosing Porville. We've received your order and will start preparing it shortly.";
                $icon = '✓';
                $message = "Your order {$orderNumber} has been successfully placed. We'll keep you updated as your order progresses.";
                break;
        }

        return compact('subject', 'heading', 'intro', 'icon', 'message', 'partner');
    }
}
