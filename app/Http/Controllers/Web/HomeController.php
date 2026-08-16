<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\ProductService;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    protected ProductService $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index()
    {
        // Reusable builder: storefront-visible products with the relations/aggregates
        // every product card needs (category name, average rating, review count).
        $productCard = fn () => \App\Models\Product::query()
            ->where('status', true)
            ->with(['images', 'category:id,name,slug'])
            ->withAvg('reviews', 'rating')   // → reviews_avg_rating
            ->withCount('reviews');          // → reviews_count

        // Cache the entire homepage data payload for 1 hour to prevent heavy queries on every load.
        // NOTE: this payload is user-agnostic, so it never contains per-user state (e.g. wishlist).
        $data = \Illuminate\Support\Facades\Cache::remember(
            \App\Support\Cache\CacheKeys::HOMEPAGE_DATA,
            \App\Support\Cache\CacheKeys::TTL_STANDARD,
            function () use ($productCard) {
            return [
                'banners' => \App\Models\Banner::where('status', true)->orderBy('sort_order')->get(),
                // Prefer admin-curated homepage categories; otherwise fall back to the
                // first active top-level categories so the grid is always populated.
                'homepageCategories' => (function () {
                    $base = \App\Models\Category::where('status', true);
                    $curated = (clone $base)->where('show_on_homepage', true)
                        ->orderBy('sort_order')->take(4)->get();

                    return $curated->isNotEmpty()
                        ? $curated
                        : (clone $base)->whereNull('parent_id')
                            ->orderBy('sort_order')->orderBy('name')->take(4)->get();
                })(),
                'featuredProducts' => $productCard()->where('is_featured', true)->take(8)->get(),
                'bestSellers' => $productCard()->where('is_best_seller', true)->take(8)->get(),
                'trendingProducts' => $productCard()->where('is_trending', true)->take(8)->get(),
                'newArrivals' => $productCard()->orderBy('created_at', 'desc')->take(8)->get(),
                'testimonials' => \App\Models\Testimonial::where('status', true)->orderBy('sort_order')->take(6)->get(),
                'blogs' => \App\Models\Blog::published()->orderBy('published_at', 'desc')->take(3)->get(),
            ];
        });

        // Per-user wishlist product IDs — resolved outside the shared cache so the
        // homepage payload stays user-agnostic while cards can still show saved state.
        $data['wishlistIds'] = \Illuminate\Support\Facades\Auth::check()
            ? \App\Models\Wishlist::where('user_id', \Illuminate\Support\Facades\Auth::id())->pluck('product_id')->all()
            : [];

        return view('home', $data);
    }
}
