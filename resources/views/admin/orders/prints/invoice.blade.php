<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice - #{{ $order->order_number }}</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 14px; color: #333; margin: 0; padding: 20px; }
        .header { width: 100%; border-bottom: 2px solid #eee; padding-bottom: 20px; margin-bottom: 30px; }
        .header td { vertical-align: top; }
        .title { font-size: 28px; font-weight: bold; color: #4f46e5; margin: 0; }
        .text-right { text-align: right; }
        .text-gray { color: #666; }
        .details-table { width: 100%; margin-bottom: 30px; }
        .details-table td { vertical-align: top; width: 50%; }
        .section-title { font-size: 12px; text-transform: uppercase; color: #999; margin-bottom: 10px; font-weight: bold; }
        .items-table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        .items-table th { background: #f9fafb; padding: 12px; text-align: left; font-weight: bold; border-top: 1px solid #eee; border-bottom: 1px solid #eee; }
        .items-table td { padding: 12px; border-bottom: 1px solid #f3f4f6; }
        .items-table .text-center { text-align: center; }
        .items-table .text-right { text-align: right; }
        .totals-table { width: 300px; float: right; border-collapse: collapse; }
        .totals-table td { padding: 8px 0; text-align: right; }
        .totals-table .label { text-align: left; color: #666; }
        .grand-total { font-size: 18px; font-weight: bold; color: #4f46e5; border-top: 2px solid #eee; padding-top: 10px; }
        .footer { text-align: center; color: #999; font-size: 12px; border-top: 1px solid #eee; padding-top: 20px; clear: both; margin-top: 40px; }
    </style>
</head>
<body>
    <table class="header">
        <tr>
            <td>
                <h1 class="title">INVOICE</h1>
                <p class="text-gray" style="margin-top: 5px; margin-bottom: 0;">Order #{{ $order->order_number }}</p>
                <p class="text-gray" style="margin-top: 2px;">Date: {{ $order->created_at->format('M d, Y') }}</p>
            </td>
            <td class="text-right">
                <h2 style="margin: 0; color: #333; font-size: 20px;">{{ setting('site_name', 'Kare Ons Herbal') }}</h2>
                <p class="text-gray" style="margin-top: 5px; font-size: 12px; line-height: 1.4;">
                    {!! nl2br(e(setting('address', "123 Herbal Avenue, Wellness City\nNature State, 10001"))) !!}
                </p>
                <p class="text-gray" style="margin-top: 2px; font-size: 12px;">{{ setting('site_email', 'support@kareons.com') }} | {{ setting('site_phone', '+91 98765 43210') }}</p>
            </td>
        </tr>
    </table>

    <table class="details-table">
        <tr>
            <td>
                <div class="section-title">Billed To</div>
                @if($order->user || $order->address)
                    <strong style="font-size: 16px;">{{ $order->address->full_name ?? $order->user->name }}</strong><br>
                    <span style="color: #555; line-height: 1.6;">
                        {{ $order->address->address_line_1 ?? '' }}<br>
                        @if($order->address->address_line_2) {{ $order->address->address_line_2 }}<br> @endif
                        {{ $order->address->city ?? '' }}, {{ $order->address->state ?? '' }} {{ $order->address->postal_code ?? '' }}<br>
                        {{ $order->address->country ?? '' }}<br>
                        Phone: {{ $order->address->phone ?? '' }}
                    </span>
                @endif
            </td>
            <td class="text-right">
                <div class="section-title">Payment Details</div>
                <p style="color: #555; margin: 0 0 5px 0;">Method: <strong>{{ strtoupper($order->payment_method) }}</strong></p>
                <p style="color: #555; margin: 0;">Status: <strong>{{ ucfirst($order->payment_status) }}</strong></p>
            </td>
        </tr>
    </table>

    <table class="items-table">
        <thead>
            <tr>
                <th>Item</th>
                <th>SKU</th>
                <th class="text-center">Qty</th>
                <th class="text-right">Price</th>
                <th class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
            <tr>
                <td><strong>{{ $item->product_name }}</strong></td>
                <td class="text-gray">{{ $item->sku }}</td>
                <td class="text-center">{{ $item->quantity }}</td>
                <td class="text-right">₹{{ number_format($item->price, 2) }}</td>
                <td class="text-right">₹{{ number_format($item->total, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <table class="totals-table">
        <tr>
            <td class="label">Subtotal</td>
            <td>₹{{ number_format($order->subtotal ?? $order->grand_total, 2) }}</td>
        </tr>
        @if(isset($order->discount_amount) && $order->discount_amount > 0)
        <tr>
            <td class="label">Discount</td>
            <td>-₹{{ number_format($order->discount_amount, 2) }}</td>
        </tr>
        @endif
        @if(isset($order->shipping_charge) && $order->shipping_charge > 0)
        <tr>
            <td class="label">Shipping</td>
            <td>₹{{ number_format($order->shipping_charge, 2) }}</td>
        </tr>
        @endif
        <tr>
            <td class="label grand-total">Total</td>
            <td class="grand-total">₹{{ number_format($order->grand_total, 2) }}</td>
        </tr>
    </table>

    <div class="footer">
        Thank you for shopping with {{ setting('site_name', 'Kare Ons') }}!
    </div>
</body>
</html>
