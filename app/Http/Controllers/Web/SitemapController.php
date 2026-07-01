<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Page;
use App\Models\Product;
use Illuminate\Http\Request;

class SitemapController extends Controller
{
    public function index()
    {
        $products = Product::where('status', true)->get();
        $blogs = Blog::where('status', true)->get();
        $pages = Page::where('status', true)->get();

        return response()->view('sitemap.index', [
            'products' => $products,
            'blogs' => $blogs,
            'pages' => $pages,
        ])->header('Content-Type', 'text/xml');
    }
}
