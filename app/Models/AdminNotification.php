<?php

namespace App\Models;

use App\Models\ContactInquiry;
use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

/**
 * A store-wide admin notification. Prefer the typed `notify*` factory helpers
 * below over `create()` so notification copy/links stay consistent, and so a
 * failure while building a notification can never break the customer-facing
 * action that triggered it.
 */
class AdminNotification extends Model
{
    protected $fillable = [
        'type', 'title', 'message', 'url', 'icon', 'color',
        'notifiable_type', 'notifiable_id', 'data', 'read_at',
    ];

    protected function casts(): array
    {
        return [
            'data'    => 'array',
            'read_at' => 'datetime',
        ];
    }

    /* ---------------------------------------------------------------------
     | Scopes
     * ------------------------------------------------------------------- */

    public function scopeUnread(Builder $query): Builder
    {
        return $query->whereNull('read_at');
    }

    public function scopeRead(Builder $query): Builder
    {
        return $query->whereNotNull('read_at');
    }

    public function scopeOfType(Builder $query, string $type): Builder
    {
        return $query->where('type', $type);
    }

    public function getIsReadAttribute(): bool
    {
        return $this->read_at !== null;
    }

    public function markAsRead(): void
    {
        if ($this->read_at === null) {
            $this->forceFill(['read_at' => now()])->save();
        }
    }

    /* ---------------------------------------------------------------------
     | Aggregate helpers (used by the bell dropdown / badge)
     * ------------------------------------------------------------------- */

    public static function unreadCount(): int
    {
        return static::unread()->count();
    }

    public static function recent(int $limit = 8)
    {
        return static::query()->latest()->limit($limit)->get();
    }

    /* ---------------------------------------------------------------------
     | Typed factories — never let a notification failure bubble up into the
     | request that spawned it.
     * ------------------------------------------------------------------- */

    public static function record(array $attributes): ?self
    {
        try {
            return static::create($attributes);
        } catch (\Throwable $e) {
            Log::warning('Failed to create admin notification', [
                'error'      => $e->getMessage(),
                'attributes' => $attributes,
            ]);

            return null;
        }
    }

    public static function notifyNewOrder(Order $order): ?self
    {
        return static::record([
            'type'            => 'order',
            'title'           => 'New order received',
            'message'         => "Order #{$order->order_number} · ₹" . number_format((float) $order->grand_total, 2)
                                 . ' · ' . (optional($order->address)->full_name ?? optional($order->user)->name ?? 'Guest'),
            'url'             => route('admin.orders.show', $order->id),
            'icon'            => 'shopping_bag',
            'color'          => 'emerald',
            'notifiable_type' => Order::class,
            'notifiable_id'   => $order->id,
        ]);
    }

    public static function notifyLowStock(Product $product): ?self
    {
        // De-duplicate: skip if there is already an unread low-stock alert for
        // this product, so re-ordering the same item doesn't spam the feed.
        $exists = static::unread()
            ->ofType('low_stock')
            ->where('notifiable_type', Product::class)
            ->where('notifiable_id', $product->id)
            ->exists();

        if ($exists) {
            return null;
        }

        $outOfStock = (int) $product->stock_quantity <= 0;

        return static::record([
            'type'            => 'low_stock',
            'title'           => $outOfStock ? 'Product out of stock' : 'Low stock alert',
            'message'         => "{$product->name} — {$product->stock_quantity} left in stock",
            'url'             => route('admin.inventory.index'),
            'icon'            => 'warning',
            'color'          => $outOfStock ? 'red' : 'amber',
            'notifiable_type' => Product::class,
            'notifiable_id'   => $product->id,
        ]);
    }

    public static function notifyReviewPending(Review $review): ?self
    {
        return static::record([
            'type'            => 'review',
            'title'           => 'New review pending approval',
            'message'         => ($review->rating ? "{$review->rating}★ " : '')
                                 . 'on ' . (optional($review->product)->name ?? 'a product'),
            'url'             => route('admin.reviews.index'),
            'icon'            => 'rate_review',
            'color'          => 'indigo',
            'notifiable_type' => Review::class,
            'notifiable_id'   => $review->id,
        ]);
    }

    public static function notifyCustomerMessage(ContactInquiry $inquiry): ?self
    {
        return static::record([
            'type'            => 'message',
            'title'           => 'New customer message',
            'message'         => $inquiry->name . ($inquiry->subject ? ' — ' . $inquiry->subject : ''),
            'url'             => route('admin.inquiries.show', $inquiry->id),
            'icon'            => 'mail',
            'color'          => 'sky',
            'notifiable_type' => ContactInquiry::class,
            'notifiable_id'   => $inquiry->id,
        ]);
    }
}
