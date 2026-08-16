<?php

namespace App\Services;

use App\Mail\OrderPlaced;
use App\Mail\PaymentConfirmation;
use App\Models\InventoryTransaction;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

/**
 * Centralises the "a Razorpay payment succeeded / failed" transition so that
 * both the browser redirect (CheckoutController@callback) and the server-to-server
 * webhook (RazorpayWebhookController) reconcile orders identically.
 *
 * All transitions are idempotent and row-locked, so it is safe for the callback
 * and the webhook to both fire for the same payment — the order is only confirmed
 * once and the confirmation email is sent only once.
 */
class RazorpayPaymentService
{
    /**
     * Mark a payment as successful and confirm its order.
     *
     * @return bool true if THIS call performed the transition (first success),
     *              false if it was already processed.
     */
    public function markPaid(Payment $payment, ?string $razorpayPaymentId = null): bool
    {
        $result = DB::transaction(function () use ($payment, $razorpayPaymentId) {
            /** @var Payment|null $locked */
            $locked = Payment::whereKey($payment->id)->lockForUpdate()->first();

            if (! $locked || $locked->status === 'success') {
                return null; // already processed — idempotent no-op
            }

            $locked->update([
                'razorpay_payment_id' => $razorpayPaymentId ?: $locked->razorpay_payment_id,
                'status'              => 'success',
                'paid_at'             => now(),
            ]);

            $order = $locked->order;
            if ($order) {
                $order->update([
                    'payment_status' => 'paid',
                    // Only advance from the initial "pending" state; never move a
                    // shipped/delivered order backwards if a late webhook arrives.
                    'order_status'   => $order->order_status === 'pending' ? 'confirmed' : $order->order_status,
                ]);
            }

            return ['order' => $order, 'payment' => $locked];
        });

        if (! $result || ! $result['order']) {
            return false;
        }

        [$order, $confirmedPayment] = [$result['order'], $result['payment']];

        // Dispatch confirmation emails outside the transaction so the queued
        // job never races ahead of the commit. Best-effort: a mail failure
        // must never make an already-successful payment look unprocessed.
        if ($order->user) {
            try {
                Mail::to($order->user->email)->send(new OrderPlaced($order));
                Mail::to($order->user->email)->send(new PaymentConfirmation($order, $confirmedPayment));
            } catch (\Throwable $e) {
                Log::error('Order/payment confirmation email failed: ' . $e->getMessage(), ['order_id' => $order->id]);
            }
        }

        return true;
    }

    /**
     * Mark a payment (and its order) as failed. Never downgrades a
     * successful/refunded payment, and is idempotent for already-failed
     * payments so a retried callback/webhook cannot restock twice.
     *
     * Because stock is deducted at order creation (before payment), a failed
     * payment must return that stock to inventory and cancel the order —
     * otherwise abandoned Razorpay checkouts silently leak stock.
     */
    public function markFailed(Payment $payment): void
    {
        $order = DB::transaction(function () use ($payment) {
            /** @var Payment|null $locked */
            $locked = Payment::whereKey($payment->id)->lockForUpdate()->first();

            // Idempotent: only the first pending -> failed transition does work.
            if (! $locked || in_array($locked->status, ['success', 'refunded', 'failed'], true)) {
                return null;
            }

            $locked->update(['status' => 'failed']);

            $order = $locked->order;
            if (! $order) {
                return null;
            }

            // Restore stock for each line and log the reversal, mirroring the
            // admin cancellation path. Skipped for orders already in a
            // stock-restored state to avoid double restocking.
            if (! in_array($order->order_status, ['cancelled', 'returned'], true)) {
                foreach ($order->items as $item) {
                    if ($item->product) {
                        $item->product->increment('stock_quantity', $item->quantity);

                        InventoryTransaction::create([
                            'product_id'   => $item->product_id,
                            'user_id'      => $order->user_id,
                            'type'         => 'order_cancellation',
                            'quantity'     => $item->quantity, // Positive for restocking
                            'reference_id' => $order->order_number,
                            'notes'        => 'Stock restored due to failed/abandoned payment.',
                        ]);
                    }
                }
            }

            $order->update([
                'payment_status'       => 'failed',
                'order_status'         => 'cancelled',
                'cancellation_reason'  => $order->cancellation_reason ?? 'Payment failed or was not completed.',
            ]);

            return $order;
        });

        // Best-effort: never let a mail failure surface as a payment-processing error.
        if ($order && $order->user) {
            try {
                Mail::to($order->user->email)->send(new \App\Mail\OrderCancelled($order));
            } catch (\Throwable $e) {
                Log::error('Order cancellation email failed: ' . $e->getMessage(), ['order_id' => $order->id]);
            }
        }
    }
}
