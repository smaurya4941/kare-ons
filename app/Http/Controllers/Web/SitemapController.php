<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Category;
use App\Models\Page;
use App\Models\Product;
use Illuminate\Http\Request;

class SitemapController extends Controller
{
    public function index()
    {
        // Only surface content that is both published/active AND explicitly
        // allowed to be indexed — a noindexed record has no business being
        // advertised to crawlers via the sitemap.
        $products = Product::indexable()->get();
        $categories = Category::indexable()->get();
        $blogs = Blog::indexable()->get();
        $pages = Page::indexable()->get();

        return response()->view('sitemap.index', [
            'products' => $products,
            'categories' => $categories,
            'blogs' => $blogs,
            'pages' => $pages,
        ])->header('Content-Type', 'text/xml');
    }
}
