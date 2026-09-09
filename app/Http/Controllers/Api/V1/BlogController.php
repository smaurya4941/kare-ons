<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\BlogResource;
use App\Models\Blog;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $blogs = Blog::where('status', true)
            ->when($request->filled('category'), fn ($q) => $q->where('category', $request->string('category')))
            ->latest('published_at')
            ->paginate(9)
            ->withQueryString();

        $categories = Blog::where('status', true)
            ->whereNotNull('category')
            ->select('category')
            ->distinct()
            ->pluck('category');

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
