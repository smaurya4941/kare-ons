<x-mail::message>
# Great News! Your Order Has Shipped

Hi {{ $order->user->name ?? $order->address->full_name ?? 'Customer' }},

Your order **{{ $order->order_number }}** from **{{ setting('site_name') }}** has been shipped and is on its way to you!

<x-mail::panel>
**Order Date:** {{ $order->created_at->format('M d, Y - h:i A') }}<br>
**Status:** Shipped<br>
@if($order->courier_name)
**Courier:** {{ $order->courier_name }}<br>
@endif
@if($order->tracking_number)
**Tracking Number:** {{ $order->tracking_number }}<br>
@endif
@if($order->expected_delivery_date)
**Expected Delivery:** {{ \Illuminate\Support\Carbon::parse($order->expected_delivery_date)->format('M d, Y') }}
@endif
</x-mail::panel>

### Delivery Address:
{{ $order->address->full_name }}<br>
{{ $order->address->address_line_1 }}<br>
@if($order->address->address_line_2) {{ $order->address->address_line_2 }}<br> @endif
{{ $order->address->city }}, {{ $order->address->state }} - {{ $order->address->postal_code }}

@if($order->tracking_url)
<x-mail::button :url="$order->tracking_url">
Track Your Package
</x-mail::button>
@elseif($order->user_id)
<x-mail::button :url="route('orders.show', $order->id)">
Track Your Order
</x-mail::button>
@endif

Thanks,<br>
The {{ setting('site_name') }} Team
</x-mail::message>
