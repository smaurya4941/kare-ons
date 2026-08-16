<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Support\Cache\CacheKeys;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ShopController extends Controller
{
    /**
     * Allowed sort keys — prevents arbitrary ORDER BY injection.
     */
    private const SORT_OPTIONS = [
        'latest'     => ['created_at', 'desc'],
        'price_low'  => ['price', 'asc'],
        'price_high' => ['price', 'desc'],
        'name_asc'   => ['name', 'asc'],
        'name_desc'  => ['name', 'desc'],
    ];

    public function index(Request $request)
    {
        // Validate query-string inputs to prevent type errors and SQL issues
        $request->validate([
            'search'    => 'nullable|string|max:100',
            'category'  => 'nullable|string|max:100',
            'min_price' => 'nullable|numeric|min:0|max:100000',
            'max_price' => 'nullable|numeric|min:0|max:100000|gte:min_price',
            'sort'      => 'nullable|string|in:' . implode(',', array_keys(self::SORT_OPTIONS)),
        ]);

        $query = Product::where('status', 1)
            ->with('category')
            ->withAvg('reviews', 'rating')   // → reviews_avg_rating
            ->withCount('reviews');          // → reviews_count

        // Filter by Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('short_description', 'like', "%{$search}%");
            });
        }

        // Filter by Category
        if ($request->filled('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category)->where('status', 1);
            });
        }

        // Filter by Price Range
        if ($request->filled('min_price')) {
            $query->where('price', '>=', (float) $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', (float) $request->max_price);
        }

        // Sorting — key-mapped, no raw injection possible
        [$column, $direction] = self::SORT_OPTIONS[$request->get('sort', 'latest')] ?? ['created_at', 'desc'];
        $query->orderBy($column, $direction);

        $products = $query->paginate(12)->withQueryString();

        // Filter dropdown data — same active categories on every page of every
        // search/sort combo, so it's cached separately from the (uncacheable,
        // per-query) paginated product list itself.
        $categories = Cache::remember(CacheKeys::SHOP_CATEGORIES, CacheKeys::TTL_STANDARD, function () {
            return Category::where('status', 1)->orderBy('name')->get();
        });

        // Per-user wishlist product IDs — resolved once to avoid an N+1 per card.
        $wishlistIds = \Illuminate\Support\Facades\Auth::check()
            ? \App\Models\Wishlist::where('user_id', \Illuminate\Support\Facades\Auth::id())->pluck('product_id')->all()
            : [];

        return view('shop.index', compact('products', 'categories', 'wishlistIds'));
    }
}
