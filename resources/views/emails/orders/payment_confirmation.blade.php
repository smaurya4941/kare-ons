<x-mail::message>
# Payment Successful ✓

Hi {{ $order->user->name ?? $order->address->full_name ?? 'Customer' }},

We've successfully received your payment for order **#{{ $order->order_number }}**.

<x-mail::panel>
**Order:** #{{ $order->order_number }}<br>
**Amount Paid:** ₹{{ number_format($payment->amount, 2) }}<br>
@if($payment->razorpay_payment_id)
**Payment ID:** {{ $payment->razorpay_payment_id }}<br>
@endif
**Paid On:** {{ ($payment->paid_at ?? $payment->updated_at)->format('M d, Y - h:i A') }}
</x-mail::panel>

Your order is now confirmed and will be processed shortly. Please keep this email for your records.

@if($order->user_id)
<x-mail::button :url="route('orders.show', $order->id)">
View Order Details
</x-mail::button>
@endif

Thanks for shopping with us,<br>
The {{ setting('site_name') }} Team
</x-mail::message>
