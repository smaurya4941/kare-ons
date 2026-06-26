<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Display the customer's order history.
     */
    public function index(Request $request)
    {
        $orders = Auth::user()
            ->orders()
            ->with(['items.product', 'address'])
            ->latest()
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }
}
