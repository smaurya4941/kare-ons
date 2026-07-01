<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function index()
    {
        $wishlists = auth()->user()->wishlists()->with('product')->latest()->get();
        return view('wishlist.index', compact('wishlists'));
    }

    public function toggle(Request $request, Product $product)
    {
        $user = auth()->user();
        $wishlist = $user->wishlists()->where('product_id', $product->id)->first();

        if ($wishlist) {
            $wishlist->delete();
            $status = 'removed';
            $message = 'Product removed from wishlist.';
        } else {
            $user->wishlists()->create(['product_id' => $product->id]);
            $status = 'added';
            $message = 'Product added to wishlist.';
        }

        if ($request->wantsJson()) {
            return response()->json([
                'status' => $status,
                'message' => $message,
                'wishlist_count' => $user->wishlists()->count()
            ]);
        }

        return back()->with('success', $message);
    }

    public function destroy(Product $product)
    {
        auth()->user()->wishlists()->where('product_id', $product->id)->delete();
        
        return back()->with('success', 'Product removed from wishlist.');
    }
}
