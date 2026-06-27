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
    private const MAX_QUANTITY_PER_ITEM = 10;

    public function index()
    {
        $cartItems = $this->getCartItems();

        $subtotal = 0;
        foreach ($cartItems as $item) {
            // Guard against orphaned cart items (product deleted)
            if (! $item->product) {
                $item->delete();
                continue;
            }
            $price     = $item->product->sale_price ?? $item->product->price;
            $subtotal += $price * $item->quantity;
        }

        // Re-fetch after possible deletions
        $cartItems = $this->getCartItems();

        $shipping = $subtotal > 500 ? 0 : 50;
        $discount = 0;
        $total    = $subtotal + $shipping - $discount;

        return view('cart.index', compact('cartItems', 'subtotal', 'shipping', 'discount', 'total'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|integer|min:1|max:' . self::MAX_QUANTITY_PER_ITEM,
        ]);

        $product = Product::findOrFail($request->product_id);

        if (! $product->status) {
            return back()->with('error', 'This product is currently unavailable.');
        }

        if ($product->stock_quantity < $request->quantity) {
            return back()->with('error', "Only {$product->stock_quantity} unit(s) available in stock.");
        }

        $sessionId = Session::getId();
        $userId    = Auth::id();

        // Check if item already in cart
        $cartItem = CartItem::where('product_id', $product->id)
            ->when($userId, fn($q) => $q->where('user_id', $userId),
                           fn($q) => $q->where('session_id', $sessionId))
            ->first();

        try {
            if ($cartItem) {
                $newQuantity = $cartItem->quantity + $request->quantity;

                if ($newQuantity > self::MAX_QUANTITY_PER_ITEM) {
                    return back()->with('error', 'You can add a maximum of ' . self::MAX_QUANTITY_PER_ITEM . ' units per item.');
                }
                if ($product->stock_quantity < $newQuantity) {
                    return back()->with('error', "Only {$product->stock_quantity} unit(s) available. You already have {$cartItem->quantity} in your cart.");
                }

                $cartItem->update(['quantity' => $newQuantity]);
            } else {
                CartItem::create([
                    'user_id'    => $userId,
                    'session_id' => $userId ? null : $sessionId,
                    'product_id' => $product->id,
                    'quantity'   => $request->quantity,
                ]);
            }
        } catch (\Exception $e) {
            report($e);
            return back()->with('error', 'Failed to add item to cart due to an unexpected error.');
        }

        if ($request->action === 'buy') {
            return redirect()->route('checkout.index');
        }

        return redirect()->route('cart.index')->with('success', "'{$product->name}' added to your cart.");
    }

    public function update(Request $request, CartItem $cartItem)
    {
        if (! $this->ownsCartItem($cartItem)) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'quantity' => 'required|integer|min:1|max:' . self::MAX_QUANTITY_PER_ITEM,
        ]);

        if (! $cartItem->product || ! $cartItem->product->status) {
            $cartItem->delete();
            return back()->with('error', 'This product is no longer available and has been removed from your cart.');
        }

        if ($cartItem->product->stock_quantity < $request->quantity) {
            return back()->with('error', "Only {$cartItem->product->stock_quantity} unit(s) available.");
        }

        try {
            $cartItem->update(['quantity' => $request->quantity]);
            return back()->with('success', 'Cart updated.');
        } catch (\Exception $e) {
            report($e);
            return back()->with('error', 'Failed to update cart due to an unexpected error.');
        }
    }

    public function destroy(CartItem $cartItem)
    {
        if (! $this->ownsCartItem($cartItem)) {
            abort(403, 'Unauthorized action.');
        }

        try {
            $cartItem->delete();
            return back()->with('success', 'Item removed from cart.');
        } catch (\Exception $e) {
            report($e);
            return back()->with('error', 'Failed to remove item from cart due to an unexpected error.');
        }
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    protected function getCartItems()
    {
        if (Auth::check()) {
            return CartItem::with('product')->where('user_id', Auth::id())->get();
        }
        return CartItem::with('product')->where('session_id', Session::getId())->get();
    }

    protected function ownsCartItem(CartItem $cartItem): bool
    {
        if (Auth::check()) {
            return (int) $cartItem->user_id === Auth::id();
        }
        return $cartItem->session_id === Session::getId();
    }
}
