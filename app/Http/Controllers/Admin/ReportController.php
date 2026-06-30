<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use App\Models\Coupon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    protected $validTabs = ['sales', 'customer', 'coupon', 'inventory', 'profit', 'tax', 'order'];

    public function index(Request $request, $tab = 'sales')
    {
        if (!in_array($tab, $this->validTabs)) {
            abort(404);
        }

        $startDate = $request->get('start_date', now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));

        $data = $this->getReportData($tab, $startDate, $endDate);

        if ($request->has('export')) {
            $format = $request->get('export');
            if ($format === 'csv') {
                return $this->exportCSV($tab, $data, $startDate, $endDate);
            } elseif ($format === 'pdf') {
                return view('admin.reports.print', compact('tab', 'data', 'startDate', 'endDate'));
            }
        }

        return view('admin.reports.tabs.' . $tab, compact('tab', 'data', 'startDate', 'endDate'));
    }

    protected function getReportData($tab, $startDate, $endDate)
    {
        $start = \Carbon\Carbon::parse($startDate)->startOfDay();
        $end = \Carbon\Carbon::parse($endDate)->endOfDay();

        switch ($tab) {
            case 'sales':
                return Order::select(
                        DB::raw('DATE(created_at) as date'),
                        DB::raw('COUNT(id) as total_orders'),
                        DB::raw('SUM(subtotal) as subtotal'),
                        DB::raw('SUM(discount_amount) as discounts'),
                        DB::raw('SUM(shipping_charge) as shipping'),
                        DB::raw('SUM(grand_total) as revenue')
                    )
                    ->whereBetween('created_at', [$start, $end])
                    ->where('order_status', '!=', 'cancelled')
                    ->groupBy('date')
                    ->orderBy('date', 'desc')
                    ->get();

            case 'customer':
                return User::where('role', 'customer')
                    ->withCount(['orders' => function($q) use ($start, $end) {
                        $q->whereBetween('created_at', [$start, $end]);
                    }])
                    ->withSum(['orders' => function($q) use ($start, $end) {
                        $q->whereBetween('created_at', [$start, $end])
                          ->where('order_status', '!=', 'cancelled');
                    }], 'grand_total')
                    ->orderByDesc('orders_sum_grand_total')
                    ->get()
                    ->filter(fn($u) => $u->orders_count > 0);

            case 'coupon':
                return Coupon::withCount(['usages' => function($q) use ($start, $end) {
                        $q->whereBetween('created_at', [$start, $end]);
                    }])
                    ->get()
                    ->filter(fn($c) => $c->usages_count > 0);

            case 'inventory':
                return Product::withSum(['orderItems as reserved_stock' => function($q) {
                        $q->whereHas('order', function($query) {
                            $query->whereIn('order_status', ['pending', 'processing', 'confirmed']);
                        });
                    }], 'quantity')
                    ->get();

            case 'profit':
            case 'tax':
                // For profit/tax we can show a summary table by day or month
                return Order::select(
                        DB::raw('DATE(created_at) as date'),
                        DB::raw('COUNT(id) as total_orders'),
                        DB::raw('SUM(subtotal) as subtotal'),
                        DB::raw('SUM(tax_amount) as tax_amount'),
                        DB::raw('SUM(discount_amount) as discounts'),
                        DB::raw('SUM(shipping_charge) as shipping'),
                        DB::raw('SUM(grand_total) as revenue')
                    )
                    ->whereBetween('created_at', [$start, $end])
                    ->where('order_status', '!=', 'cancelled')
                    ->groupBy('date')
                    ->orderBy('date', 'desc')
                    ->get();

            case 'order':
                return Order::with('user')
                    ->whereBetween('created_at', [$start, $end])
                    ->orderBy('created_at', 'desc')
                    ->get();
        }

        return collect();
    }

    protected function exportCSV($tab, $data, $startDate, $endDate)
    {
        $filename = "{$tab}_report_{$startDate}_to_{$endDate}.csv";
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = [];
        $callback = function() {};

        if ($tab === 'sales') {
            $columns = ['Date', 'Total Orders', 'Subtotal', 'Discounts', 'Shipping', 'Revenue'];
            $callback = function() use($data, $columns) {
                $file = fopen('php://output', 'w');
                fputcsv($file, $columns);
                foreach ($data as $row) {
                    fputcsv($file, [$row->date, $row->total_orders, $row->subtotal, $row->discounts, $row->shipping, $row->revenue]);
                }
                fclose($file);
            };
        } elseif ($tab === 'customer') {
            $columns = ['Customer Name', 'Email', 'Orders Placed', 'Total Spent'];
            $callback = function() use($data, $columns) {
                $file = fopen('php://output', 'w');
                fputcsv($file, $columns);
                foreach ($data as $row) {
                    fputcsv($file, [$row->name, $row->email, $row->orders_count, $row->orders_sum_grand_total ?? 0]);
                }
                fclose($file);
            };
        } elseif ($tab === 'coupon') {
            $columns = ['Coupon Code', 'Type', 'Value', 'Times Used'];
            $callback = function() use($data, $columns) {
                $file = fopen('php://output', 'w');
                fputcsv($file, $columns);
                foreach ($data as $row) {
                    fputcsv($file, [$row->code, $row->type, $row->value, $row->usages_count]);
                }
                fclose($file);
            };
        } elseif ($tab === 'inventory') {
            $columns = ['Product Name', 'SKU', 'Available Stock', 'Reserved Stock', 'Total Stock Value'];
            $callback = function() use($data, $columns) {
                $file = fopen('php://output', 'w');
                fputcsv($file, $columns);
                foreach ($data as $row) {
                    $val = ($row->sale_price ?? $row->price) * $row->stock_quantity;
                    fputcsv($file, [$row->name, $row->sku, $row->stock_quantity, $row->reserved_stock ?? 0, $val]);
                }
                fclose($file);
            };
        } elseif ($tab === 'profit' || $tab === 'tax') {
            $columns = ['Date', 'Orders', 'Revenue', 'Tax Collected', 'Net Profit (Rev - Tax)'];
            $callback = function() use($data, $columns) {
                $file = fopen('php://output', 'w');
                fputcsv($file, $columns);
                foreach ($data as $row) {
                    fputcsv($file, [$row->date, $row->total_orders, $row->revenue, $row->tax_amount, $row->revenue - $row->tax_amount]);
                }
                fclose($file);
            };
        } elseif ($tab === 'order') {
            $columns = ['Order ID', 'Customer', 'Date', 'Status', 'Payment Method', 'Total'];
            $callback = function() use($data, $columns) {
                $file = fopen('php://output', 'w');
                fputcsv($file, $columns);
                foreach ($data as $row) {
                    fputcsv($file, [$row->order_number, $row->user->name ?? 'Guest', $row->created_at->format('Y-m-d H:i'), $row->order_status, $row->payment_method, $row->grand_total]);
                }
                fclose($file);
            };
        }

        return response()->stream($callback, 200, $headers);
    }
}
