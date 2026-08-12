<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\RazorpayPayment;
use App\Services\RazorpayService;
use App\Services\OrderStatusNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RazorpayController extends Controller
{
    public function __construct(private RazorpayService $razorpayService) {}

    /** GET /checkout/pay/{order} */
    public function show(Order $order)
    {
        $this->authorizeOrder($order);

        if ($order->payment_status === 'paid') {
            return redirect()->route('frontend.order.success', $order->id);
        }

        $razorpayPayment = $order->razorpayPayments()->latest()->firstOrFail();
        $keyId           = config('razorpay.key_id');
        $paymentMethod   = $order->payment_method; // 'online' or 'upi'

        return view('frontend.razorpay-payment', compact('order', 'razorpayPayment', 'keyId', 'paymentMethod'));
    }

    /** POST /checkout/razorpay/callback (CSRF-exempt, rate-limited) */
    public function callback(Request $request)
    {
        $request->validate([
            'razorpay_order_id'   => 'required|string|min:1',
            'razorpay_payment_id' => 'required|string|min:1',
            'razorpay_signature'  => 'required|string|min:1',
        ]);

        $rzpOrderId  = $request->input('razorpay_order_id');
        $rzpPayId    = $request->input('razorpay_payment_id');
        $rzpSig      = $request->input('razorpay_signature');

        $razorpayPayment = RazorpayPayment::where('razorpay_order_id', $rzpOrderId)->firstOrFail();
        $order           = $razorpayPayment->order;

        if ($this->razorpayService->verifySignature($rzpOrderId, $rzpPayId, $rzpSig)) {
            DB::transaction(function () use ($razorpayPayment, $order, $rzpPayId, $rzpSig) {
                $razorpayPayment->update([
                    'razorpay_payment_id' => $rzpPayId,
                    'razorpay_signature'  => $rzpSig,
                    'status'              => 'paid',
                ]);

                $order->update(['payment_status' => 'paid']);

                $this->clearCartForOrder($order);
            });

            OrderStatusNotificationService::notifyStatusChange($order->fresh());

            return redirect()->route('frontend.order.success', $order->id);
        }

        $razorpayPayment->update(['status' => 'failed']);

        return redirect()
            ->route('frontend.razorpay.payment', $order->id)
            ->with('error', 'Payment verification failed. Please try again or contact support.');
    }

    /** GET /checkout/razorpay/cancel/{order} */
    public function cancel(Order $order)
    {
        $this->authorizeOrder($order);

        if ($order->payment_status === 'paid') {
            return redirect()->route('frontend.order.success', $order->id);
        }

        DB::transaction(function () use ($order) {
            $order->update([
                'status'         => 'cancelled',
                'payment_status' => 'failed',
            ]);

            $order->razorpayPayments()->latest()->update(['status' => 'cancelled']);
        });

        return redirect()->route('frontend.cart')->with('success', 'Your order has been cancelled.');
    }

    private function authorizeOrder(Order $order): void
    {
        if ($order->user_id !== auth('web_frontend')->id()) {
            abort(403);
        }
    }

    private function clearCartForOrder(Order $order): void
    {
        $checkoutDay = $order->delivery_day ?? 'today';
        $fullCart    = session('cart', []);

        $remainingCart = array_filter(
            $fullCart,
            fn ($item) => \App\Support\ProductDayPricing::normalizeDay($item['pricing_day'] ?? 'today') !== $checkoutDay
        );

        session(['cart' => $remainingCart]);
        session()->forget('applied_coupon_' . $checkoutDay);
        session()->forget('checkout_delivery_day');
    }
}
