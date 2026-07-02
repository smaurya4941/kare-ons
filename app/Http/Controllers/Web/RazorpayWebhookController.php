<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Services\RazorpayPaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Razorpay\Api\Api;

/**
 * Server-to-server Razorpay webhook.
 *
 * This is the authoritative reconciliation path: even if the customer closes the
 * browser after paying (so CheckoutController@callback never runs), Razorpay
 * still notifies us here and the order is confirmed.
 *
 * Configure in the Razorpay Dashboard → Webhooks:
 *   URL:    https://your-domain.com/webhooks/razorpay
 *   Events: payment.captured, payment.failed, order.paid
 *   Secret: must match Admin → Settings → Payment → Webhook Secret
 */
class RazorpayWebhookController extends Controller
{
    public function handle(Request $request, RazorpayPaymentService $service)
    {
        $secret = setting('razorpay_webhook_secret');

        if (empty($secret)) {
            Log::warning('Razorpay webhook received but no webhook secret is configured.');
            // 200 so Razorpay does not keep retrying a misconfigured endpoint.
            return response()->json(['status' => 'not configured'], 200);
        }

        $body      = $request->getContent();
        $signature = $request->header('X-Razorpay-Signature', '');

        // Verify the payload came from Razorpay (HMAC-SHA256 of the raw body with the secret).
        try {
            $api = new Api(setting('razorpay_key'), setting('razorpay_secret'));
            $api->utility->verifyWebhookSignature($body, $signature, $secret);
        } catch (\Throwable $e) {
            Log::warning('Razorpay webhook signature verification failed.', ['error' => $e->getMessage()]);
            return response()->json(['status' => 'invalid signature'], 400);
        }

        $payload = $request->json()->all();
        $event   = $payload['event'] ?? null;

        $razorpayOrderId   = data_get($payload, 'payload.payment.entity.order_id')
                             ?? data_get($payload, 'payload.order.entity.id');
        $razorpayPaymentId = data_get($payload, 'payload.payment.entity.id');

        if (empty($razorpayOrderId)) {
            return response()->json(['status' => 'no order reference'], 200);
        }

        $payment = Payment::where('razorpay_order_id', $razorpayOrderId)->first();

        if (! $payment) {
            Log::info('Razorpay webhook: no matching payment.', ['razorpay_order_id' => $razorpayOrderId, 'event' => $event]);
            return response()->json(['status' => 'payment not found'], 200);
        }

        switch ($event) {
            case 'payment.captured':
            case 'order.paid':
                $service->markPaid($payment, $razorpayPaymentId);
                break;

            case 'payment.failed':
                $service->markFailed($payment);
                break;

            default:
                // Acknowledge other events (refunds, disputes, etc.) without acting.
                break;
        }

        return response()->json(['status' => 'ok'], 200);
    }
}
