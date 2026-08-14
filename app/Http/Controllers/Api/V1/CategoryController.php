<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::where('status', 1)
            ->whereNull('parent_id')
            ->with(['children' => fn ($q) => $q->where('status', 1)->orderBy('sort_order')])
            ->orderBy('sort_order')
            ->get();

        return CategoryResource::collection($categories);
    }

    public function show(string $slug)
    {
        $category = Category::where('slug', $slug)
            ->where('status', 1)
            ->with(['children' => fn ($q) => $q->where('status', 1)->orderBy('sort_order')])
            ->firstOrFail();

        return new CategoryResource($category);
    }
}
