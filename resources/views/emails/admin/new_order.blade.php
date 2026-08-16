<x-mail::message>
# 🛒 New {{ setting('site_name') }} Order

<x-mail::panel>
**Order:** #{{ $order->order_number }}<br>
**Customer:** {{ $order->user->name ?? $order->address->full_name ?? 'Guest' }}<br>
**Amount:** ₹{{ number_format($order->grand_total, 2) }}<br>
**Payment Method:** {{ strtoupper($order->payment_method) }}<br>
**Products:** {{ $order->items->count() }}
</x-mail::panel>

### Items:
<x-mail::table>
| Product | Qty | Total |
|:---|:---:|---:|
@foreach($order->items as $item)
| {{ $item->product_name }} | {{ $item->quantity }} | ₹{{ number_format($item->total, 2) }} |
@endforeach
</x-mail::table>

<x-mail::button :url="route('admin.orders.show', $order->id)">
Login to Admin Panel →
</x-mail::button>

{{ setting('site_name') }} Admin Notifications
</x-mail::message>
