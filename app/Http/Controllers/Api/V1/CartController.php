<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\CartItemResource;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;

/**
 * Mirrors Web\CartController minus the guest/session branch — the API
 * requires auth:sanctum for every cart route.
 */
class CartController extends Controller
{
    private const MAX_QUANTITY_PER_ITEM = 10;

    public function index(Request $request)
    {
        $user = $request->user();

        $cartItems = $this->getCartItems($user);

        $subtotal = 0;
        foreach ($cartItems as $item) {
            if (! $item->product) {
                $item->delete();
                continue;
            }
            $subtotal += ($item->product->sale_price ?? $item->product->price) * $item->quantity;
        }

        $cartItems = $this->getCartItems($user);

        $shippingCharge = (float) setting('shipping_charge', 0);
        $freeShippingThreshold = (float) setting('free_shipping_amount', 0);
        $shipping = ($freeShippingThreshold > 0 && $subtotal >= $freeShippingThreshold) ? 0 : $shippingCharge;
        $total = $subtotal + $shipping;

        return response()->json([
            'data' => [
                'items' => CartItemResource::collection($cartItems),
                'subtotal' => round($subtotal, 2),
                'shipping' => round($shipping, 2),
                'total' => round($total, 2),
                'cart_count' => (int) $cartItems->sum('quantity'),
            ],
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1|max:'.self::MAX_QUANTITY_PER_ITEM,
        ]);

        $user = $request->user();
        $product = Product::findOrFail($request->product_id);

        if (! $product->status) {
            return $this->error('This product is currently unavailable.');
        }

        if ($product->stock_quantity < $request->quantity) {
            return $this->error("Only {$product->stock_quantity} unit(s) available in stock.");
        }

        $cartItem = CartItem::where('product_id', $product->id)->where('user_id', $user->id)->first();

        if ($cartItem) {
            $newQuantity = $cartItem->quantity + $request->quantity;

            if ($newQuantity > self::MAX_QUANTITY_PER_ITEM) {
                return $this->error('You can add a maximum of '.self::MAX_QUANTITY_PER_ITEM.' units per item.');
            }
            if ($product->stock_quantity < $newQuantity) {
                return $this->error("Only {$product->stock_quantity} unit(s) available. You already have {$cartItem->quantity} in your cart.");
            }

            $cartItem->update(['quantity' => $newQuantity]);
        } else {
            $cartItem = CartItem::create([
                'user_id' => $user->id,
                'session_id' => null,
                'product_id' => $product->id,
                'quantity' => $request->quantity,
            ]);
        }

        return response()->json([
            'message' => "'{$product->name}' added to your cart.",
            'data' => new CartItemResource($cartItem->fresh('product')),
            'cart_count' => $this->cartCount($user),
        ], 201);
    }

    public function update(Request $request, CartItem $cartItem)
    {
        if ((int) $cartItem->user_id !== $request->user()->id) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'quantity' => 'required|integer|min:1|max:'.self::MAX_QUANTITY_PER_ITEM,
        ]);

        if (! $cartItem->product || ! $cartItem->product->status) {
            $cartItem->delete();
            return $this->error('This product is no longer available and has been removed from your cart.', 410);
        }

        if ($cartItem->product->stock_quantity < $request->quantity) {
            return $this->error("Only {$cartItem->product->stock_quantity} unit(s) available.");
        }

        $cartItem->update(['quantity' => $request->quantity]);

        return new CartItemResource($cartItem->fresh('product'));
    }

    public function destroy(Request $request, CartItem $cartItem)
    {
        if ((int) $cartItem->user_id !== $request->user()->id) {
            abort(403, 'Unauthorized action.');
        }

        $cartItem->delete();

        return response()->json([
            'message' => 'Item removed from cart.',
            'cart_count' => $this->cartCount($request->user()),
        ]);
    }

    protected function getCartItems($user)
    {
        return CartItem::with('product')->where('user_id', $user->id)->get();
    }

    protected function cartCount($user): int
    {
        return (int) CartItem::where('user_id', $user->id)->sum('quantity');
    }

    protected function error(string $message, int $status = 422)
    {
        return response()->json(['message' => $message], $status);
    }
}
