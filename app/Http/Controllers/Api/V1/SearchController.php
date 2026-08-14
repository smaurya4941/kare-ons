<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * Mirrors Web\SearchController@suggest.
 */
class SearchController extends Controller
{
    private const MIN_QUERY_LENGTH = 2;

    private const MAX_RESULTS = 6;

    public function suggest(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'q' => 'nullable|string|max:100',
        ]);

        $term = trim($validated['q'] ?? '');

        if (Str::length($term) < self::MIN_QUERY_LENGTH) {
            return response()->json([
                'query' => $term,
                'results' => [],
                'total' => 0,
            ]);
        }

        $escaped = addcslashes($term, '%_\\');
        $like = "%{$escaped}%";

        $baseQuery = Product::query()
            ->where('status', 1)
            ->where(function ($q) use ($like) {
                $q->where('name', 'like', $like)
                    ->orWhere('sku', 'like', $like)
                    ->orWhere('short_description', 'like', $like);
            });

        $total = (clone $baseQuery)->count();

        $products = $baseQuery
            ->with('category:id,name,slug')
            ->orderByRaw('CASE WHEN name LIKE ? THEN 0 ELSE 1 END', [$like])
            ->latest('id')
            ->limit(self::MAX_RESULTS)
            ->get(['id', 'name', 'slug', 'sku', 'price', 'sale_price', 'main_image', 'category_id']);

        $results = $products->map(function (Product $product) {
            $effectivePrice = $product->sale_price ?? $product->price;

            return [
                'id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'image' => $product->main_image ? asset('storage/'.$product->main_image) : null,
                'category' => $product->category?->name,
                'price' => (float) $effectivePrice,
                'original_price' => $product->sale_price !== null ? (float) $product->price : null,
                'on_sale' => (bool) $product->sale_price,
            ];
        });

        return response()->json([
            'query' => $term,
            'results' => $results,
            'total' => $total,
        ]);
    }
}
