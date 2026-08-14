<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SearchController extends Controller
{
    /**
     * Minimum characters before we hit the database.
     * Keeps single-letter queries (which match nearly everything) off the DB.
     */
    private const MIN_QUERY_LENGTH = 2;

    /** Maximum suggestions returned for the dropdown. */
    private const MAX_RESULTS = 6;

    /**
     * Live search autocomplete — returns lightweight product suggestions as JSON.
     *
     * Consumed by the Alpine.js search box in the header. Public + rate limited
     * (see the `throttle:search` limiter in web.php / AppServiceProvider).
     */
    public function suggest(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'q' => 'nullable|string|max:100',
        ]);

        $term = trim($validated['q'] ?? '');

        // Below the threshold we return an empty set rather than an error so the
        // frontend can simply clear the dropdown without special-casing.
        if (Str::length($term) < self::MIN_QUERY_LENGTH) {
            return response()->json([
                'query'   => $term,
                'results' => [],
                'total'   => 0,
            ]);
        }

        // Escape LIKE wildcards so a user typing "%" or "_" searches literally.
        $escaped = addcslashes($term, '%_\\');
        $like    = "%{$escaped}%";

        $baseQuery = Product::query()
            ->where('status', 1)
            ->where(function ($q) use ($like) {
                $q->where('name', 'like', $like)
                  ->orWhere('sku', 'like', $like)
                  ->orWhere('short_description', 'like', $like);
            });

        // Total matches so the "View all results" footer can show a real count.
        $total = (clone $baseQuery)->count();

        $products = $baseQuery
            ->with('category:id,name,slug')
            // Prioritise name matches over description-only matches, then newest.
            ->orderByRaw('CASE WHEN name LIKE ? THEN 0 ELSE 1 END', [$like])
            ->latest('id')
            ->limit(self::MAX_RESULTS)
            ->get(['id', 'name', 'slug', 'sku', 'price', 'sale_price', 'main_image', 'category_id']);

        $results = $products->map(function (Product $product) {
            $effectivePrice = $product->sale_price ?? $product->price;

            return [
                'id'             => $product->id,
                'name'           => $product->name,
                'url'            => route('product.show', $product->slug),
                'image'          => image_url($product->main_image),
                'category'       => $product->category?->name,
                'price'          => '₹' . number_format((float) $effectivePrice, 2),
                'original_price' => $product->sale_price
                    ? '₹' . number_format((float) $product->price, 2)
                    : null,
                'on_sale'        => (bool) $product->sale_price,
            ];
        });

        return response()->json([
            'query'   => $term,
            'results' => $results,
            'total'   => $total,
            'shop_url' => route('shop.index', ['search' => $term]),
        ]);
    }
}
