<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\WishlistResource;
use App\Models\Product;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function index(Request $request)
    {
        $wishlists = $request->user()->wishlists()->with('product.category')->latest()->get();

        return WishlistResource::collection($wishlists);
    }

    public function toggle(Request $request, Product $product)
    {
        $user = $request->user();
        $wishlist = $user->wishlists()->where('product_id', $product->id)->first();

        if ($wishlist) {
            $wishlist->delete();
            $status = 'removed';
        } else {
            $user->wishlists()->create(['product_id' => $product->id]);
            $status = 'added';
        }

        return response()->json([
            'status' => $status,
            'wishlist_count' => $user->wishlists()->count(),
        ]);
    }

    public function destroy(Request $request, Product $product)
    {
        $request->user()->wishlists()->where('product_id', $product->id)->delete();

        return response()->json(['message' => 'Product removed from wishlist.']);
    }
}
