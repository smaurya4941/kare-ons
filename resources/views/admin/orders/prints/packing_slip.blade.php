<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Packing Slip - #{{ $order->order_number }}</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 14px; color: #333; margin: 0; padding: 20px; }
        .header { width: 100%; border-bottom: 2px solid #eee; padding-bottom: 20px; margin-bottom: 30px; }
        .header td { vertical-align: top; }
        .title { font-size: 28px; font-weight: bold; color: #333; margin: 0; }
        .text-right { text-align: right; }
        .text-gray { color: #666; }
        .details-table { width: 100%; margin-bottom: 30px; }
        .details-table td { vertical-align: top; }
        .section-title { font-size: 12px; text-transform: uppercase; color: #999; margin-bottom: 10px; font-weight: bold; }
        .items-table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        .items-table th { background: #f9fafb; padding: 12px; text-align: left; font-weight: bold; border-top: 1px solid #eee; border-bottom: 1px solid #eee; }
        .items-table td { padding: 12px; border-bottom: 1px solid #f3f4f6; }
        .items-table .text-center { text-align: center; }
        .check-box { width: 16px; height: 16px; border: 2px solid #ccc; display: inline-block; }
        .notes-box { width: 100%; height: 100px; border: 1px solid #eee; margin-top: 10px; }
    </style>
</head>
<body>
    <table class="header">
        <tr>
            <td>
                <h1 class="title">PACKING SLIP</h1>
                <p class="text-gray" style="margin-top: 5px; margin-bottom: 0;">Order #{{ $order->order_number }}</p>
                <p class="text-gray" style="margin-top: 2px;">Date: {{ $order->created_at->format('M d, Y') }}</p>
            </td>
            <td class="text-right">
                <h2 style="margin: 0; color: #333; font-size: 20px;">{{ setting('site_name', 'Kare Ons Herbal') }}</h2>
                <p class="text-gray" style="margin-top: 5px; font-size: 12px; line-height: 1.4;">
                    {!! nl2br(e(setting('address', "123 Herbal Avenue, Wellness City\nNature State, 10001"))) !!}
                </p>
            </td>
        </tr>
    </table>

    <div style="margin-bottom: 30px;">
        <div class="section-title">Ship To</div>
        @if($order->address)
            <strong style="font-size: 18px;">{{ $order->address->full_name }}</strong><br>
            <span style="color: #555; line-height: 1.6; font-size: 14px;">
                {{ $order->address->address_line_1 }}<br>
                @if($order->address->address_line_2) {{ $order->address->address_line_2 }}<br> @endif
                {{ $order->address->city }}, {{ $order->address->state }} {{ $order->address->postal_code }}<br>
                {{ $order->address->country }}<br>
                Phone: {{ $order->address->phone }}
            </span>
        @endif
    </div>

    <table class="items-table">
        <thead>
            <tr>
                <th class="text-center" style="width: 50px;">Check</th>
                <th>Item</th>
                <th>SKU</th>
                <th class="text-center" style="width: 80px;">Qty</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
            <tr>
                <td class="text-center"><div class="check-box"></div></td>
                <td><strong style="font-size: 15px;">{{ $item->product_name }}</strong></td>
                <td class="text-gray">{{ $item->sku }}</td>
                <td class="text-center"><strong style="font-size: 18px;">{{ $item->quantity }}</strong></td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <div style="margin-top: 50px; border-top: 1px solid #eee; padding-top: 20px;">
        <h4 style="margin: 0 0 10px 0; color: #333;">Packing Notes</h4>
        <div class="notes-box"></div>
    </div>
</body>
</html>
