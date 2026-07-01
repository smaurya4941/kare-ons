<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Shipping Label - #{{ $order->order_number }}</title>
    <style>
        @page { margin: 10px; }
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 14px; color: #000; margin: 0; padding: 0; }
        .label-container { width: 100%; max-width: 100%; box-sizing: border-box; }
        .from-section { border-bottom: 2px dashed #000; padding-bottom: 15px; margin-bottom: 15px; }
        .from-title { font-weight: bold; font-size: 12px; margin-bottom: 5px; }
        .to-section { margin-top: 20px; }
        .to-title { font-weight: bold; font-size: 18px; margin-bottom: 10px; }
        .to-name { font-size: 22px; font-weight: bold; text-transform: uppercase; margin-bottom: 5px; }
        .to-address { font-size: 18px; text-transform: uppercase; line-height: 1.4; }
        .footer-section { border-top: 4px solid #000; margin-top: 30px; padding-top: 15px; text-align: center; }
        .order-number { font-size: 24px; font-family: monospace; font-weight: bold; letter-spacing: 2px; margin: 10px 0; }
        .barcode-placeholder { height: 60px; background: repeating-linear-gradient(90deg, #000, #000 2px, #fff 2px, #fff 4px, #000 4px, #000 7px, #fff 7px, #fff 10px); width: 80%; margin: 0 auto; }
    </style>
</head>
<body>
    <div class="label-container">
        
        <!-- From / Sender -->
        <div class="from-section">
            <div class="from-title">FROM:</div>
            <div><strong>{{ setting('site_name', 'Kare Ons Herbal') }}</strong></div>
            <div style="font-size: 12px; line-height: 1.4; margin-top: 3px;">
                {!! nl2br(e(setting('address', "123 Herbal Avenue\nWellness City, Nature State 10001"))) !!}
            </div>
        </div>

        <!-- To / Recipient -->
        <div class="to-section">
            <div class="to-title">SHIP TO:</div>
            @if($order->address)
            <div class="to-name">{{ $order->address->full_name }}</div>
            <div class="to-address">
                {{ $order->address->address_line_1 }}<br>
                @if($order->address->address_line_2) {{ $order->address->address_line_2 }}<br> @endif
                {{ $order->address->city }}, {{ $order->address->state }} {{ $order->address->postal_code }}<br>
                <strong>{{ $order->address->country }}</strong><br>
                <span style="font-family: monospace; font-size: 16px; display: block; margin-top: 10px;">PH: {{ $order->address->phone }}</span>
            </div>
            @else
            <div>No address provided.</div>
            @endif
        </div>

        <!-- Order / Barcode placeholder -->
        <div class="footer-section">
            <div style="font-size: 12px; font-weight: bold;">ORDER NUMBER</div>
            <div class="order-number">{{ $order->order_number }}</div>
            <!-- Mock barcode -->
            <div class="barcode-placeholder"></div>
            <div style="font-size: 10px; margin-top: 5px;">{{ \Illuminate\Support\Str::uuid() }}</div>
        </div>

    </div>
</body>
</html>
