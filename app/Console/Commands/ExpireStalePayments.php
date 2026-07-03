<?php

namespace App\Console\Commands;

use App\Models\Payment;
use App\Services\RazorpayPaymentService;
use Illuminate\Console\Command;

/**
 * Belt-and-suspenders reconciliation for Razorpay orders where neither the
 * browser callback nor the webhook ever fired (customer closed the tab and the
 * webhook was missed/misconfigured). Such payments sit 'pending' forever while
 * their stock stays deducted.
 *
 * This marks any pending payment older than the threshold as failed via the
 * shared service — which restocks and cancels the order idempotently, so it is
 * safe even if a late webhook arrives afterwards.
 */
class ExpireStalePayments extends Command
{
    protected $signature = 'payments:expire-stale {--minutes=30 : Age in minutes after which a pending payment is considered abandoned}';

    protected $description = 'Fail and restock Razorpay payments left pending past the threshold';

    public function handle(RazorpayPaymentService $service): int
    {
        $minutes = max(1, (int) $this->option('minutes'));
        $cutoff  = now()->subMinutes($minutes);

        $stale = Payment::where('status', 'pending')
            ->where('created_at', '<', $cutoff)
            ->get();

        if ($stale->isEmpty()) {
            $this->info('No stale pending payments to expire.');
            return self::SUCCESS;
        }

        foreach ($stale as $payment) {
            $service->markFailed($payment);
            $this->line("Expired payment #{$payment->id} (order {$payment->order?->order_number}).");
        }

        $this->info("Expired {$stale->count()} stale pending payment(s).");

        return self::SUCCESS;
    }
}
