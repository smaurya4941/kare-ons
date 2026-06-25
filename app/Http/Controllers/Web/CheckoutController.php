<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CheckoutController extends Controller
{
    public function index()
    {
        $cartItems = $this->getCartItems();
        
        if ($cartItems->isEmpty()) {
            return redirect()->route('shop.index')->with('error', 'Your cart is empty.');
        }

        $subtotal = 0;
        foreach ($cartItems as $item) {
            $price = $item->product->sale_price ?? $item->product->price;
            $subtotal += $price * $item->quantity;
        }

        $shipping = $subtotal > 500 ? 0 : 50;
        $total = $subtotal + $shipping;

        // Fetch user addresses if logged in
        $addresses = Auth::check() ? Auth::user()->addresses : collect();

        return view('checkout.index', compact('cartItems', 'subtotal', 'shipping', 'total', 'addresses'));
    }

    public function store(Request $request)
    {
        // For Phase 11 & 12, this will handle form submission and initialize Razorpay.
        // We will mock order placement for now, and handle Razorpay explicitly later if needed.
        
        $request->validate([
            'full_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address_line_1' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            'payment_method' => 'required|in:razorpay,cod'
        ]);

        $cartItems = $this->getCartItems();
        if ($cartItems->isEmpty()) {
            return redirect()->route('shop.index');
        }

        // Logic for creating an Order record...
        // For brevity in Phase 11 frontend, we just redirect to a success page mock or initiate payment.

        if ($request->payment_method === 'razorpay') {
            // Setup Razorpay Order...
            return redirect()->route('checkout.payment')->with('message', 'Redirecting to secure payment gateway...');
        }

        // COD Flow
        // Clear Cart
        if (Auth::check()) {
            CartItem::where('user_id', Auth::id())->delete();
        } else {
            CartItem::where('session_id', Session::getId())->delete();
        }

        return redirect()->route('checkout.success')->with('success', 'Order placed successfully!');
    }

    public function payment()
    {
        return view('checkout.payment');
    }

    public function success()
    {
        return view('checkout.success');
    }

    protected function getCartItems()
    {
        if (Auth::check()) {
            return CartItem::with('product')->where('user_id', Auth::id())->get();
        }
        return CartItem::with('product')->where('session_id', Session::getId())->get();
    }
}
