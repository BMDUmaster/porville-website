<?php

namespace App\Support;

class OrderPricing
{
    public static function deliveryCharge(): float
    {
        return round((float) config('order_pricing.delivery_charge', 25), 2);
    }

    public static function summary(float $subtotal): array
    {
        $subtotal = round(max($subtotal, 0), 2);
        $deliveryCharge = $subtotal > 0 ? self::deliveryCharge() : 0.0;
        $serviceChargePercent = ServiceChargeManager::percentage();
        $serviceCharge = ServiceChargeManager::calculate($subtotal);
        $total = round($subtotal + $deliveryCharge + $serviceCharge, 2);

        return [
            'subtotal' => $subtotal,
            'delivery_charge' => $deliveryCharge,
            'service_charge_percent' => $serviceChargePercent,
            'service_charge' => $serviceCharge,
            'total' => $total,
        ];
    }
}
