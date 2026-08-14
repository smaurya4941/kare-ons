<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ProductIndexRequest;
use App\Http\Resources\ProductCardResource;
use App\Http\Resources\ProductResource;
use App\Models\Category;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;

/**
 * Mirrors Web\ShopController@index (listing) and
 * Web\ProductController@show / ProductRepository (detail).
 */
class ProductController extends Controller
{
    public function index(ProductIndexRequest $request)
    {
        $validated = $request->validated();

        $query = Product::where('status', 1)
            ->with(['category', 'brand'])
            ->withAvg('reviews', 'rating')
            ->withCount('reviews');

        if (! empty($validated['search'])) {
            $search = $validated['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('short_description', 'like', "%{$search}%");
            });
        }

        if (! empty($validated['category'])) {
            $query->whereHas('category', function ($q) use ($validated) {
                $q->where('slug', $validated['category'])->where('status', 1);
            });
        }

        if (! empty($validated['brand'])) {
            $query->whereHas('brand', function ($q) use ($validated) {
                $q->where('slug', $validated['brand'])->where('status', 1);
            });
        }

        if (! empty($validated['min_price'])) {
            $query->where('price', '>=', (float) $validated['min_price']);
        }
        if (! empty($validated['max_price'])) {
            $query->where('price', '<=', (float) $validated['max_price']);
        }

        [$column, $direction] = ProductIndexRequest::SORT_OPTIONS[$validated['sort'] ?? 'latest'] ?? ['created_at', 'desc'];
        $query->orderBy($column, $direction);

        $perPage = $validated['per_page'] ?? 12;
        $products = $query->paginate($perPage)->withQueryString();

        $this->attachWishlistFlags($request, $products->getCollection());

        return ProductCardResource::collection($products)->additional([
            'meta' => [
                'categories' => \App\Http\Resources\CategoryResource::collection(
                    Category::where('status', 1)->orderBy('name')->get()
                ),
            ],
        ]);
    }

    public function show(Request $request, string $slug)
    {
        $product = Product::where('slug', $slug)
            ->where('status', 1)
            ->with(['category', 'brand', 'tax', 'images', 'reviews.user'])
            ->firstOrFail();

        $related = Product::where('status', 1)
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->with(['category', 'images'])
            ->inRandomOrder()
            ->take(4)
            ->get();

        $this->attachWishlistFlags($request, collect([$product])->merge($related));

        return response()->json([
            'data' => [
                'product' => new ProductResource($product),
                'related_products' => ProductCardResource::collection($related),
            ],
        ]);
    }

    /**
     * Sets a transient `in_wishlist` attribute on each product when the
     * request carries a valid Sanctum bearer token (optional auth — this
     * route has no `auth:sanctum` middleware).
     */
    protected function attachWishlistFlags(Request $request, $products): void
    {
        $user = $request->user('sanctum');
        if (! $user || $products->isEmpty()) {
            return;
        }

        $wishlistIds = Wishlist::where('user_id', $user->id)
            ->whereIn('product_id', $products->pluck('id'))
            ->pluck('product_id')
            ->all();

        foreach ($products as $product) {
            $product->in_wishlist = in_array($product->id, $wishlistIds, true);
        }
    }
}
