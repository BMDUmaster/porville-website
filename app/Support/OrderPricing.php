<?php

namespace App\Support;

use App\Models\User;

class OrderPricing
{
    public static function deliveryCharge(?User $user = null): float
    {
        return DeliveryChargeManager::amountForUser($user);
    }

    public static function summary(float $subtotal, ?User $user = null): array
    {
        $subtotal = round(max($subtotal, 0), 2);
        $deliveryCharge = $subtotal > 0 ? self::deliveryCharge($user) : 0.0;
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
