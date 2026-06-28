<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_products' => Product::count(),
            'total_orders' => Order::count(),
            'pending_orders' => Order::where('order_status', 'pending')->count(),
            'total_revenue' => Order::where('order_status', 'delivered')->sum('grand_total'),
            'total_customers' => User::where('role', 'customer')->count(),
        ];

        $thirtyDaysAgo = now()->subDays(30)->startOfDay();

        // 1. Sales Trend (Revenue last 30 days)
        $salesTrend = Order::selectRaw('DATE(created_at) as date, SUM(grand_total) as total')
            ->where('created_at', '>=', $thirtyDaysAgo)
            ->whereNotIn('order_status', ['cancelled'])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $charts = [
            'sales' => [
                'labels' => $salesTrend->pluck('date')->map(fn($d) => \Carbon\Carbon::parse($d)->format('M d')),
                'data' => $salesTrend->pluck('total'),
            ],
            'status' => [
                'labels' => ['Pending', 'Processing', 'Shipped', 'Delivered', 'Cancelled'],
                'data' => [
                    Order::where('order_status', 'pending')->count(),
                    Order::where('order_status', 'processing')->count(),
                    Order::where('order_status', 'shipped')->count(),
                    Order::where('order_status', 'delivered')->count(),
                    Order::where('order_status', 'cancelled')->count(),
                ]
            ],
        ];

        // 3. Top Products (by units sold in order_items)
        $topProducts = \App\Models\OrderItem::selectRaw('product_id, SUM(quantity) as total_sold')
            ->with('product:id,name')
            ->groupBy('product_id')
            ->orderByDesc('total_sold')
            ->take(5)
            ->get();
            
        $charts['top_products'] = [
            'labels' => $topProducts->map(fn($item) => $item->product ? $item->product->name : 'Unknown'),
            'data' => $topProducts->pluck('total_sold')
        ];

        $recentOrders = Order::with('user')->orderBy('created_at', 'desc')->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recentOrders', 'charts'));
    }
}
