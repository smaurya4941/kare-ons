<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\BannerResource;
use App\Http\Resources\BlogResource;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\ProductCardResource;
use App\Http\Resources\TestimonialResource;
use App\Models\Banner;
use App\Models\Blog;
use App\Models\Category;
use App\Models\Product;
use App\Models\Testimonial;
use App\Models\Wishlist;
use App\Support\Cache\CacheKeys;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

/**
 * Mirrors Web\HomeController@index. Payload is cached 1hr and is
 * user-agnostic (no wishlist state) — authenticated clients should fetch
 * /wishlist separately to know which cards are saved.
 */
class HomeController extends Controller
{
    public function index(Request $request)
    {
        $productCard = fn () => Product::query()
            ->where('status', true)
            ->with(['images', 'category:id,name,slug'])
            ->withAvg('reviews', 'rating')
            ->withCount('reviews');

        $data = Cache::remember(CacheKeys::HOMEPAGE_DATA, CacheKeys::TTL_STANDARD, function () use ($productCard) {
            return [
                'banners' => Banner::where('status', true)->orderBy('sort_order')->get(),
                'homepageCategories' => (function () {
                    $base = Category::where('status', true);
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
                'testimonials' => Testimonial::where('status', true)->orderBy('sort_order')->take(6)->get(),
                'blogs' => Blog::published()->orderBy('published_at', 'desc')->take(3)->get(),
            ];
        });

        // No auth:sanctum middleware on this route (it's public) — resolve the
        // sanctum guard explicitly rather than Auth::check(), which reads the
        // default 'web' session guard and would always be false for a
        // Bearer-token API caller.
        $user = $request->user('sanctum');
        $wishlistIds = $user
            ? Wishlist::where('user_id', $user->id)->pluck('product_id')->all()
            : [];

        return response()->json([
            'data' => [
                'banners' => BannerResource::collection($data['banners']),
                'homepage_categories' => CategoryResource::collection($data['homepageCategories']),
                'featured_products' => ProductCardResource::collection($data['featuredProducts']),
                'best_sellers' => ProductCardResource::collection($data['bestSellers']),
                'trending_products' => ProductCardResource::collection($data['trendingProducts']),
                'new_arrivals' => ProductCardResource::collection($data['newArrivals']),
                'testimonials' => TestimonialResource::collection($data['testimonials']),
                'blogs' => BlogResource::collection($data['blogs']),
                'wishlist_ids' => $wishlistIds,
            ],
        ]);
    }
}
