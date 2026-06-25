<?php

namespace App\Repositories\Interfaces;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface ProductRepositoryInterface
{
    public function getFeaturedProducts(int $limit = 8): Collection;
    
    public function getActiveProducts(int $perPage = 12): LengthAwarePaginator;
    
    public function findBySlug(string $slug): ?Product;
    
    public function getRelatedProducts(Product $product, int $limit = 4): Collection;
}
