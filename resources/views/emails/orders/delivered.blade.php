<x-mail::message>
# Your Order Has Been Delivered 🎉

Hi {{ $order->user->name ?? $order->address->full_name ?? 'Customer' }},

Great news! Your order **{{ $order->order_number }}** from **{{ setting('site_name') }}** has been delivered.

<x-mail::panel>
**Order Date:** {{ $order->created_at->format('M d, Y - h:i A') }}<br>
**Status:** Delivered
</x-mail::panel>

We hope you love your products. How was your experience?

@if($order->user_id)
<x-mail::button :url="route('orders.show', $order->id)">
⭐ Rate Your Order
</x-mail::button>
@endif

If anything isn't quite right, you can request a return or replacement from your order page within 7 days of delivery.

Thanks for shopping with us,<br>
The {{ setting('site_name') }} Team
</x-mail::message>
