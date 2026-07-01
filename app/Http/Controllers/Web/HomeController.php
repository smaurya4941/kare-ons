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
        // Cache the entire homepage data payload for 1 hour to prevent 8 heavy queries on every load
        $data = \Illuminate\Support\Facades\Cache::remember('homepage_data', 3600, function () {
            return [
                'banners' => \App\Models\Banner::where('status', true)->orderBy('sort_order')->get(),
                'homepageCategories' => \App\Models\Category::where('status', true)->where('show_on_homepage', true)->orderBy('sort_order')->take(4)->get(),
                'featuredProducts' => \App\Models\Product::with('images')->where('status', true)->where('is_featured', true)->take(8)->get(),
                'bestSellers' => \App\Models\Product::with('images')->where('status', true)->where('is_best_seller', true)->take(8)->get(),
                'trendingProducts' => \App\Models\Product::with('images')->where('status', true)->where('is_trending', true)->take(8)->get(),
                'newArrivals' => \App\Models\Product::with('images')->where('status', true)->orderBy('created_at', 'desc')->take(8)->get(),
                'testimonials' => \App\Models\Testimonial::where('status', true)->orderBy('sort_order')->take(6)->get(),
                'blogs' => \App\Models\Blog::where('status', 'published')->orderBy('published_at', 'desc')->take(3)->get(),
            ];
        });

        return view('home', $data);
    }
}
