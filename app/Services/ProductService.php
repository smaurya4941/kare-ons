<?php

namespace App\Services;

use App\Repositories\Interfaces\ProductRepositoryInterface;

class ProductService
{
    protected ProductRepositoryInterface $productRepository;

    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function getHomePageProducts()
    {
        return $this->productRepository->getFeaturedProducts(8);
    }

    public function getProductDetails(string $slug)
    {
        $product = $this->productRepository->findBySlug($slug);
        $relatedProducts = $this->productRepository->getRelatedProducts($product, 4);

        return [
            'product' => $product,
            'relatedProducts' => $relatedProducts
        ];
    }
}
