<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report: {{ ucfirst($tab) }}</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #333; margin: 0; padding: 20px; font-size: 14px; }
        .header { display: flex; justify-content: space-between; align-items: flex-end; border-bottom: 2px solid #ddd; padding-bottom: 10px; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 24px; color: #111; text-transform: uppercase; }
        .header p { margin: 5px 0 0 0; color: #666; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px 12px; text-align: left; }
        th { background-color: #f4f4f4; color: #333; text-transform: uppercase; font-size: 11px; font-weight: bold; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .footer { text-align: center; font-size: 10px; color: #999; margin-top: 30px; border-top: 1px solid #ddd; padding-top: 10px; }
        @media print {
            body { padding: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="no-print" style="margin-bottom: 20px; text-align: right;">
        <button onclick="window.print()" style="padding: 8px 16px; background: #4f46e5; color: #fff; border: none; border-radius: 4px; cursor: pointer;">Print PDF</button>
    </div>

    <div class="header">
        <div>
            <h1>{{ ucfirst($tab) }} Report</h1>
            <p><strong>{{ get_setting('site_name', 'Kareons Herbal') }}</strong></p>
        </div>
        <div style="text-align: right;">
            <p>Generated: {{ now()->format('M d, Y H:i') }}</p>
            <p>Period: {{ \Carbon\Carbon::parse($startDate)->format('M d, Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('M d, Y') }}</p>
        </div>
    </div>

    <table>
        <thead>
            @if($tab === 'sales')
                <tr><th>Date</th><th>Orders</th><th class="text-right">Subtotal</th><th class="text-right">Discounts</th><th class="text-right">Shipping</th><th class="text-right">Revenue</th></tr>
            @elseif($tab === 'customer')
                <tr><th>Customer Name</th><th>Email</th><th class="text-center">Orders Placed</th><th class="text-right">Total Spent</th></tr>
            @elseif($tab === 'coupon')
                <tr><th>Coupon Code</th><th>Type</th><th class="text-right">Value</th><th class="text-center">Times Used</th></tr>
            @elseif($tab === 'inventory')
                <tr><th>Product / SKU</th><th class="text-center">Available Stock</th><th class="text-center">Reserved</th><th class="text-right">Total Stock Value</th></tr>
            @elseif($tab === 'profit' || $tab === 'tax')
                <tr><th>Date</th><th class="text-center">Orders</th><th class="text-right">Revenue</th><th class="text-right">Tax Collected</th><th class="text-right">Net Profit</th></tr>
            @elseif($tab === 'order')
                <tr><th>Order ID</th><th>Customer</th><th>Date</th><th>Status</th><th>Payment</th><th class="text-right">Total</th></tr>
            @endif
        </thead>
        <tbody>
            @foreach($data as $row)
                <tr>
                    @if($tab === 'sales')
                        <td>{{ $row->date }}</td>
                        <td class="text-center">{{ $row->total_orders }}</td>
                        <td class="text-right">₹{{ number_format($row->subtotal, 2) }}</td>
                        <td class="text-right">-₹{{ number_format($row->discounts, 2) }}</td>
                        <td class="text-right">₹{{ number_format($row->shipping, 2) }}</td>
                        <td class="text-right font-bold">₹{{ number_format($row->revenue, 2) }}</td>
                    @elseif($tab === 'customer')
                        <td>{{ $row->name }}</td>
                        <td>{{ $row->email }}</td>
                        <td class="text-center">{{ $row->orders_count }}</td>
                        <td class="text-right">₹{{ number_format($row->orders_sum_grand_total, 2) }}</td>
                    @elseif($tab === 'coupon')
                        <td>{{ $row->code }}</td>
                        <td>{{ $row->type }}</td>
                        <td class="text-right">{{ $row->value }}</td>
                        <td class="text-center">{{ $row->usages_count }}</td>
                    @elseif($tab === 'inventory')
                        <td>{{ $row->name }} ({{ $row->sku }})</td>
                        <td class="text-center">{{ $row->stock_quantity }}</td>
                        <td class="text-center">{{ $row->reserved_stock ?? 0 }}</td>
                        <td class="text-right">₹{{ number_format(($row->sale_price ?? $row->price) * $row->stock_quantity, 2) }}</td>
                    @elseif($tab === 'profit' || $tab === 'tax')
                        <td>{{ $row->date }}</td>
                        <td class="text-center">{{ $row->total_orders }}</td>
                        <td class="text-right">₹{{ number_format($row->revenue, 2) }}</td>
                        <td class="text-right">₹{{ number_format($row->tax_amount, 2) }}</td>
                        <td class="text-right">₹{{ number_format($row->revenue - $row->tax_amount, 2) }}</td>
                    @elseif($tab === 'order')
                        <td>{{ $row->order_number }}</td>
                        <td>{{ $row->user->name ?? 'Guest' }}</td>
                        <td>{{ $row->created_at->format('M d, Y') }}</td>
                        <td>{{ $row->order_status }}</td>
                        <td>{{ $row->payment_method }}</td>
                        <td class="text-right">₹{{ number_format($row->grand_total, 2) }}</td>
                    @endif
                </tr>
            @endforeach
            @if($data->count() == 0)
                <tr><td colspan="6" class="text-center">No data available for the selected period.</td></tr>
            @endif
        </tbody>
    </table>

    <div class="footer">
        <p>This is a computer-generated report and requires no signature.</p>
    </div>
</body>
</html>
