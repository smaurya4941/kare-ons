<?php

namespace App\Repositories;

use App\Models\Product;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductRepository implements ProductRepositoryInterface
{
    public function getFeaturedProducts(int $limit = 8): Collection
    {
        return Product::where('status', 1)
            ->where('featured', 1)
            ->with(['category', 'images'])
            ->latest()
            ->take($limit)
            ->get();
    }

    public function getActiveProducts(int $perPage = 12): LengthAwarePaginator
    {
        return Product::where('status', 1)
            ->with(['category', 'images'])
            ->latest()
            ->paginate($perPage);
    }

    public function findBySlug(string $slug): ?Product
    {
        return Product::where('slug', $slug)
            ->where('status', 1)
            ->with(['category', 'images', 'reviews.user'])
            ->firstOrFail();
    }

    public function getRelatedProducts(Product $product, int $limit = 4): Collection
    {
        return Product::where('status', 1)
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->with(['category', 'images'])
            ->inRandomOrder()
            ->take($limit)
            ->get();
    }
}
