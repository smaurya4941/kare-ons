<x-mail::message>
# Order Confirmation - {{ $order->order_number }}

Hi {{ $order->user->name ?? $order->address->full_name ?? 'Customer' }},

Thank you for shopping with **{{ setting('site_name') }}**. 
Your order has been placed successfully and is currently being processed.

<x-mail::panel>
**Order Date:** {{ $order->created_at->format('M d, Y - h:i A') }}<br>
**Payment Method:** {{ strtoupper($order->payment_method) }}<br>
**Total Amount:** ₹{{ number_format($order->grand_total, 2) }}
</x-mail::panel>

### Delivery Address:
{{ $order->address->full_name }}<br>
{{ $order->address->address_line_1 }}<br>
@if($order->address->address_line_2) {{ $order->address->address_line_2 }}<br> @endif
{{ $order->address->city }}, {{ $order->address->state }} - {{ $order->address->postal_code }}

### Order Summary:
<x-mail::table>
| Product | Qty | Price | Total |
|:---|:---:|:---:|---:|
@foreach($order->items as $item)
| {{ $item->product_name }} | {{ $item->quantity }} | ₹{{ number_format($item->price, 2) }} | ₹{{ number_format($item->total, 2) }} |
@endforeach
</x-mail::table>

@if($order->user_id)
<x-mail::button :url="route('orders.show', $order->id)">
View Order Details
</x-mail::button>
@endif

Thanks,<br>
The {{ setting('site_name') }} Team
</x-mail::message>
