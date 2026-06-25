<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = $this->getCartItems();
        
        $subtotal = 0;
        foreach ($cartItems as $item) {
            $price = $item->product->sale_price ?? $item->product->price;
            $subtotal += $price * $item->quantity;
        }

        // For now, no taxes or shipping logic. Can be added later.
        $shipping = $subtotal > 500 ? 0 : 50; // Free shipping over 500
        $discount = 0; // Coupon discount logic can go here
        $total = $subtotal + $shipping - $discount;

        return view('cart.index', compact('cartItems', 'subtotal', 'shipping', 'discount', 'total'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $product = Product::findOrFail($request->product_id);
        
        if ($product->stock_quantity < $request->quantity) {
            return back()->with('error', 'Not enough stock available.');
        }

        $session_id = Session::getId();
        $user_id = Auth::id();

        // Check if item already in cart
        $cartItem = CartItem::where('product_id', $product->id)
            ->when($user_id, function($q) use ($user_id) {
                return $q->where('user_id', $user_id);
            }, function($q) use ($session_id) {
                return $q->where('session_id', $session_id);
            })->first();

        if ($cartItem) {
            $newQuantity = $cartItem->quantity + $request->quantity;
            if ($product->stock_quantity < $newQuantity) {
                return back()->with('error', 'Cannot add more. Not enough stock.');
            }
            $cartItem->update(['quantity' => $newQuantity]);
        } else {
            CartItem::create([
                'user_id' => $user_id,
                'session_id' => $user_id ? null : $session_id,
                'product_id' => $product->id,
                'quantity' => $request->quantity
            ]);
        }

        if ($request->action === 'buy') {
            return redirect()->route('checkout.index');
        }

        return redirect()->route('cart.index')->with('success', 'Product added to cart.');
    }

    public function update(Request $request, CartItem $cartItem)
    {
        // Authorization check to ensure user owns this cart item
        if (! $this->ownsCartItem($cartItem)) {
            abort(403);
        }

        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        if ($cartItem->product->stock_quantity < $request->quantity) {
            return back()->with('error', 'Not enough stock available.');
        }

        $cartItem->update(['quantity' => $request->quantity]);

        return back()->with('success', 'Cart updated.');
    }

    public function destroy(CartItem $cartItem)
    {
        if (! $this->ownsCartItem($cartItem)) {
            abort(403);
        }

        $cartItem->delete();
        return back()->with('success', 'Item removed from cart.');
    }

    protected function getCartItems()
    {
        if (Auth::check()) {
            return CartItem::with('product')->where('user_id', Auth::id())->get();
        }
        return CartItem::with('product')->where('session_id', Session::getId())->get();
    }

    protected function ownsCartItem(CartItem $cartItem)
    {
        if (Auth::check()) {
            return $cartItem->user_id == Auth::id();
        }
        return $cartItem->session_id == Session::getId();
    }
}
