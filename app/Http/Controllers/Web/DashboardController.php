<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Show the customer dashboard with real order stats.
     */
    public function index()
    {
        $user = Auth::user();

        $totalOrders   = $user->orders()->count();
        $pendingOrders = $user->orders()->where('order_status', 'pending')->count();
        $deliveredOrders = $user->orders()->where('order_status', 'delivered')->count();
        $savedAddresses = $user->addresses()->count();

        $recentOrders = $user->orders()
            ->with(['items.product'])
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'totalOrders',
            'pendingOrders',
            'deliveredOrders',
            'savedAddresses',
            'recentOrders'
        ));
    }
}
