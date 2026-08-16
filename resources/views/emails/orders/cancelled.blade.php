<x-mail::message>
# Order Cancelled

Hi {{ $order->user->name ?? $order->address->full_name ?? 'Customer' }},

Your {{ setting('site_name') }} order **#{{ $order->order_number }}** has been cancelled.

<x-mail::panel>
**Order Date:** {{ $order->created_at->format('M d, Y - h:i A') }}<br>
**Total Amount:** ₹{{ number_format($order->grand_total, 2) }}<br>
@if($order->cancellation_reason)
**Reason:** {{ $order->cancellation_reason }}<br>
@endif
**Refund Status:** {{ ucfirst($order->refund_status ?? 'none') }}
</x-mail::panel>

@if(in_array($order->payment_status, ['paid', 'refunded']) || ($order->refund_status ?? 'none') !== 'none')
If a payment was already made for this order, any applicable refund will be processed to your original payment method. Refunds typically reflect within 5–7 business days.
@endif

@if($order->user_id)
<x-mail::button :url="route('orders.show', $order->id)">
View Order Details
</x-mail::button>
@endif

If you have any questions about this cancellation, please reach out to our support team.

Thanks,<br>
The {{ setting('site_name') }} Team
</x-mail::message>
