<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index()
    {
        $blogs = Blog::where('status', true)->latest('published_at')->paginate(9);
        $categories = Blog::where('status', true)->select('category')->distinct()->pluck('category');
        return view('blog.index', compact('blogs', 'categories'));
    }

    public function show(string $slug)
    {
        $blog = Blog::where('slug', $slug)->where('status', true)->firstOrFail();
        
        $relatedBlogs = Blog::where('status', true)
            ->where('category', $blog->category)
            ->where('id', '!=', $blog->id)
            ->latest('published_at')
            ->take(3)
            ->get();

        return view('blog.show', compact('blog', 'relatedBlogs'));
    }
}
