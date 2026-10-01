<?php

namespace App\Support;

use App\Models\Notification;
use App\Models\Product;
use App\Models\ProductSlot;
use App\Models\ProductSlotAlert;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

/**
 * Product-specific ordering windows. Products without any active slot are
 * always orderable; products with slots can only be added to the cart and
 * checked out while one of their windows is open.
 */
class ProductSlotManager
{
    /** @var array<int, Collection<int, ProductSlot>>|null */
    private static ?array $slotsByProduct = null;

    /** @var array<int, int>|null */
    private static ?array $alertedProductIds = null;

    /**
     * Ordering status for a product, or null when it has no slot restriction.
     *
     * @return array{open: bool, state: string, badge: string, message: string, ranges: array<int, string>, ends_at: ?Carbon, next_start: ?Carbon}|null
     */
    public static function status(Product|int $product, ?Carbon $now = null): ?array
    {
        $productId = $product instanceof Product ? $product->id : $product;
        $slots = self::slotsByProduct()[$productId] ?? null;

        if (! $slots || $slots->isEmpty()) {
            return null;
        }

        $now = ($now ?? now())->copy()->timezone(config('app.timezone'));
        $today = $now->copy()->startOfDay();
        $tomorrow = $today->copy()->addDay();

        $windows = [];
        foreach ($slots as $slot) {
            $days = $slot->isDaily() ? [$today, $tomorrow] : [$slot->slot_date];
            foreach ($days as $day) {
                [$start, $end] = $slot->windowOn($day);
                $windows[] = ['start' => $start, 'end' => $end];
            }
        }

        $current = collect($windows)
            ->filter(fn ($w) => $w['start']->lte($now) && $w['end']->gt($now))
            ->sortByDesc(fn ($w) => $w['end']->timestamp)
            ->first();

        $nextStart = collect($windows)
            ->filter(fn ($w) => $w['start']->gt($now))
            ->sortBy(fn ($w) => $w['start']->timestamp)
            ->first()['start'] ?? null;

        $endedToday = collect($windows)->contains(
            fn ($w) => $w['end']->lte($now) && $w['end']->isSameDay($now)
        );

        $ranges = $slots
            ->reject(fn (ProductSlot $slot) => $slot->isExpired($now))
            ->map(fn (ProductSlot $slot) => ($slot->isDaily() ? 'Daily' : $slot->slot_date->format('j M')) . ' · ' . $slot->timeRangeLabel())
            ->values()
            ->all();

        if ($current) {
            $endLabel = $current['end']->format('g:i A');

            return [
                'open' => true,
                'state' => 'open',
                'badge' => $current['start']->format('g:i A') . ' – ' . $endLabel,
                'message' => "Order before {$endLabel} today. This product is only available in its ordering slot.",
                'ranges' => $ranges,
                'ends_at' => $current['end'],
                'next_start' => $nextStart,
            ];
        }

        $nextLabel = $nextStart ? self::whenLabel($nextStart, $now) : null;

        if ($nextStart && ! $endedToday) {
            return [
                'open' => false,
                'state' => 'upcoming',
                'badge' => 'Opens ' . $nextLabel,
                'message' => "Ordering for this product opens {$nextLabel}. Tap Notify Me and we'll tell you when the slot starts.",
                'ranges' => $ranges,
                'ends_at' => null,
                'next_start' => $nextStart,
            ];
        }

        return [
            'open' => false,
            'state' => 'timeout',
            'badge' => 'Time Out',
            'message' => $nextLabel
                ? "Today's ordering time is over. Next slot opens {$nextLabel} — tap Notify Me to get an alert."
                : "The ordering time for this product is over. Tap Notify Me and we'll tell you when a new slot opens.",
            'ranges' => $ranges,
            'ends_at' => null,
            'next_start' => $nextStart,
        ];
    }

    /**
     * Live countdown for a slot status: time left while open, or time until
     * it opens (when that is within a day). Null for Time Out.
     *
     * @return array{mode: string, target: Carbon, text: string}|null
     */
    public static function timer(?array $status, ?Carbon $now = null): ?array
    {
        if (! $status) {
            return null;
        }

        $now ??= now();
        $mode = $status['open'] ? 'open' : 'upcoming';
        $target = $status['open'] ? $status['ends_at'] : $status['next_start'];

        if (! $target || ($mode === 'upcoming' && $status['state'] !== 'upcoming')) {
            return null;
        }

        $seconds = max(0, $target->getTimestamp() - $now->getTimestamp());

        if ($mode === 'upcoming' && $seconds > 86400) {
            return null; // far away: the "Opens 5 Oct, 10:00 AM" label reads better
        }

        return [
            'mode' => $mode,
            'target' => $target,
            'text' => sprintf('%02d:%02d:%02d', intdiv($seconds, 3600), intdiv($seconds % 3600, 60), $seconds % 60),
        ];
    }

    public static function isOrderable(Product|int $product, ?Carbon $now = null): bool
    {
        $status = self::status($product, $now);

        return $status === null || $status['open'];
    }

    /**
     * The orderable product whose open slot closes first — shown in the header.
     *
     * @return array{product: Product, ends_at: Carbon, badge: string}|null
     */
    public static function endingSoonest(?Carbon $now = null): ?array
    {
        $productIds = array_keys(self::slotsByProduct());

        if (! $productIds) {
            return null;
        }

        return Product::active()
            ->notEnquiryOnly()
            ->whereIn('products.id', $productIds)
            ->get()
            ->reject(fn (Product $product) => $product->is_out_of_stock)
            ->map(function (Product $product) use ($now) {
                $status = self::status($product, $now);

                return $status && $status['open']
                    ? ['product' => $product, 'ends_at' => $status['ends_at'], 'badge' => $status['badge']]
                    : null;
            })
            ->filter()
            ->sortBy(fn ($entry) => $entry['ends_at']->timestamp)
            ->first();
    }

    public static function unavailableMessage(Product $product): string
    {
        $status = self::status($product);

        return $product->name . ': ' . ($status['message'] ?? 'not available to order right now.');
    }

    /**
     * Product ids the signed-in customer is waiting on (pending Notify Me).
     */
    public static function alertedProductIds(): array
    {
        if (self::$alertedProductIds !== null) {
            return self::$alertedProductIds;
        }

        $userId = Auth::guard('web_frontend')->id();

        try {
            self::$alertedProductIds = $userId
                ? ProductSlotAlert::pending()->where('user_id', $userId)->pluck('product_id')->map(fn ($id) => (int) $id)->all()
                : [];
        } catch (Throwable) {
            self::$alertedProductIds = [];
        }

        return self::$alertedProductIds;
    }

    public static function subscribe(int $userId, int $productId): void
    {
        ProductSlotAlert::query()->firstOrCreate([
            'user_id' => $userId,
            'product_id' => $productId,
            'notified_at' => null,
        ]);

        self::$alertedProductIds = null;
    }

    /**
     * Run the alert check at most once a minute, after the response is sent,
     * so it works on shared hosting even without a cron job.
     */
    public static function queueDueAlertCheck(): void
    {
        try {
            if (Cache::add('product-slot-alerts:checked', true, 60)) {
                dispatch(fn () => self::sendDueAlerts())->afterResponse();
            }
        } catch (Throwable $e) {
            Log::warning('Product slot alert check skipped: ' . $e->getMessage());
        }
    }

    /**
     * Notify customers whose product has an open slot again. Returns the
     * number of alerts sent.
     */
    public static function sendDueAlerts(?Carbon $now = null): int
    {
        try {
            $alerts = ProductSlotAlert::pending()->with(['user', 'product'])->get();
        } catch (Throwable) {
            return 0; // tables not migrated yet
        }

        $sent = 0;

        foreach ($alerts->groupBy('product_id') as $productAlerts) {
            $product = $productAlerts->first()->product;

            if (! $product || ! self::isOrderable($product, $now)) {
                continue;
            }

            $status = self::status($product, $now);
            $until = $status && $status['ends_at'] ? ' until ' . $status['ends_at']->format('g:i A') : '';
            $subject = "{$product->name} is available to order now - Porville";
            $message = "Good news! {$product->name} is open for orders now{$until}. Order before the slot closes.";
            $link = route('frontend.product.show', $product->slug);

            foreach ($productAlerts as $alert) {
                // Claim the alert first so parallel runs never notify twice.
                $claimed = ProductSlotAlert::whereKey($alert->id)->whereNull('notified_at')->update(['notified_at' => now()]);

                if (! $claimed || ! $alert->user) {
                    continue;
                }

                try {
                    Notification::create([
                        'recipient_id' => $alert->user_id,
                        'subject' => $subject,
                        'message' => $message,
                        'sent_by' => null,
                    ]);
                } catch (Throwable $e) {
                    Log::error('Slot alert in-app notification failed: ' . $e->getMessage());
                }

                if ($alert->user->email) {
                    try {
                        Mail::raw(implode("\n", [
                            "Hello {$alert->user->name},",
                            '',
                            $message,
                            '',
                            'Order now: ' . $link,
                            '',
                            'Warm Regards,',
                            'Porville Team',
                        ]), function ($mail) use ($alert, $subject) {
                            $mail->to($alert->user->email)->subject($subject);
                        });
                    } catch (Throwable $e) {
                        Log::error('Slot alert email failed for ' . $alert->user->email . ': ' . $e->getMessage());
                    }
                }

                $sent++;
            }
        }

        return $sent;
    }

    /**
     * Forget per-request memoised data (after admin edits, and in tests).
     */
    public static function flush(): void
    {
        self::$slotsByProduct = null;
        self::$alertedProductIds = null;
    }

    /**
     * @return array<int, Collection<int, ProductSlot>>
     */
    private static function slotsByProduct(): array
    {
        if (self::$slotsByProduct !== null) {
            return self::$slotsByProduct;
        }

        try {
            $slots = ProductSlot::query()->where('is_active', true)->get()->keyBy('id');
            $links = $slots->isEmpty()
                ? collect()
                : DB::table('product_slot_product')->whereIn('product_slot_id', $slots->keys())->get();
        } catch (Throwable) {
            return self::$slotsByProduct = []; // tables not migrated yet
        }

        $map = [];
        foreach ($links as $link) {
            $map[(int) $link->product_id] ??= collect();
            $map[(int) $link->product_id]->push($slots[$link->product_slot_id]);
        }

        return self::$slotsByProduct = $map;
    }

    private static function whenLabel(Carbon $moment, Carbon $now): string
    {
        if ($moment->isSameDay($now)) {
            return 'at ' . $moment->format('g:i A');
        }

        if ($moment->isSameDay($now->copy()->addDay())) {
            return 'tomorrow ' . $moment->format('g:i A');
        }

        return $moment->format('j M, g:i A');
    }
}
