<?php

namespace App\Services;

use App\Mail\OrderPlaced;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
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
        $order = DB::transaction(function () use ($payment, $razorpayPaymentId) {
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

            return $order;
        });

        if (! $order) {
            return false;
        }

        // Dispatch the confirmation email outside the transaction so the queued
        // job never races ahead of the commit.
        if ($order->user) {
            Mail::to($order->user->email)->send(new OrderPlaced($order));
        }

        return true;
    }

    /**
     * Mark a payment (and its order) as failed. Never downgrades a successful/refunded payment.
     */
    public function markFailed(Payment $payment): void
    {
        DB::transaction(function () use ($payment) {
            /** @var Payment|null $locked */
            $locked = Payment::whereKey($payment->id)->lockForUpdate()->first();

            if (! $locked || in_array($locked->status, ['success', 'refunded'], true)) {
                return;
            }

            $locked->update(['status' => 'failed']);

            if ($locked->order) {
                $locked->order->update(['payment_status' => 'failed']);
            }
        });
    }
}
