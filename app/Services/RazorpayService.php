<?php

namespace App\Services;

use Razorpay\Api\Api;
use RuntimeException;

class RazorpayService
{
    private Api $api;

    public function __construct()
    {
        $keyId     = config('razorpay.key_id');
        $keySecret = config('razorpay.key_secret');

        if (empty($keyId) || empty($keySecret)) {
            throw new RuntimeException('Razorpay API credentials are not configured.');
        }

        $this->api = new Api($keyId, $keySecret);
    }

    /**
     * Create a Razorpay order.
     *
     * @param  int    $amountPaise  Total in paise (rupees × 100)
     * @param  string $receipt      Porville order_number used as receipt identifier
     * @return array{id: string, amount: int, currency: string}
     */
    public function createOrder(int $amountPaise, string $receipt): array
    {
        $order = $this->api->order->create([
            'amount'          => $amountPaise,
            'currency'        => 'INR',
            'receipt'         => $receipt,
            'payment_capture' => 1,
        ]);

        return [
            'id'       => $order->id,
            'amount'   => $order->amount,
            'currency' => $order->currency,
        ];
    }

    /**
     * Verify Razorpay payment signature.
     *
     * Computes HMAC-SHA256(razorpay_order_id|razorpay_payment_id, key_secret)
     * and performs a constant-time comparison against the provided signature.
     */
    public function verifySignature(
        string $razorpayOrderId,
        string $razorpayPaymentId,
        string $razorpaySignature
    ): bool {
        $payload  = $razorpayOrderId . '|' . $razorpayPaymentId;
        $expected = hash_hmac('sha256', $payload, config('razorpay.key_secret'));

        return hash_equals($expected, $razorpaySignature);
    }
}
