<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Lightweight product shape for listings/rails (shop grid, home page,
 * search suggestions, related products). Use ProductResource for the
 * full product-detail payload.
 */
class ProductCardResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $effectivePrice = $this->sale_price ?? $this->price;

        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'sku' => $this->sku,
            'short_description' => $this->short_description,
            'price' => (float) $this->price,
            'sale_price' => $this->sale_price !== null ? (float) $this->sale_price : null,
            'effective_price' => (float) $effectivePrice,
            'on_sale' => $this->sale_price !== null,
            'discount_percent' => $this->sale_price !== null && (float) $this->price > 0
                ? (int) round((($this->price - $this->sale_price) / $this->price) * 100)
                : null,
            'main_image' => image_url($this->main_image),
            'in_stock' => (int) $this->stock_quantity > 0,
            'stock_quantity' => (int) $this->stock_quantity,
            'is_featured' => (bool) $this->is_featured,
            'is_best_seller' => (bool) $this->is_best_seller,
            'is_trending' => (bool) $this->is_trending,
            'rating_avg' => $this->reviews_avg_rating !== null ? round((float) $this->reviews_avg_rating, 1) : null,
            'reviews_count' => (int) ($this->reviews_count ?? 0),
            'category' => $this->whenLoaded('category', fn () => [
                'id' => $this->category->id,
                'name' => $this->category->name,
                'slug' => $this->category->slug,
            ]),
            'in_wishlist' => $this->when(isset($this->in_wishlist), fn () => (bool) $this->in_wishlist),
        ];
    }
}
