<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\BlogResource;
use App\Models\Blog;

class BlogController extends Controller
{
    public function index()
    {
        $blogs = Blog::where('status', true)->latest('published_at')->paginate(9);
        $categories = Blog::where('status', true)->select('category')->distinct()->pluck('category');

        return BlogResource::collection($blogs)->additional([
            'meta' => ['categories' => $categories],
        ]);
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

        return response()->json([
            'data' => [
                'blog' => new BlogResource($blog),
                'related_blogs' => BlogResource::collection($relatedBlogs),
            ],
        ]);
    }
}
