<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            'description' => $this->description,
            'price' => (float) $this->price,
            'sale_price' => $this->sale_price !== null ? (float) $this->sale_price : null,
            'effective_price' => (float) $effectivePrice,
            'on_sale' => $this->sale_price !== null,
            'stock_quantity' => (int) $this->stock_quantity,
            'in_stock' => (int) $this->stock_quantity > 0,
            'weight' => $this->weight !== null ? (float) $this->weight : null,
            'pack_size' => $this->pack_size,
            'main_image' => $this->main_image ? asset('storage/'.$this->main_image) : null,
            'images' => $this->whenLoaded('images', fn () => $this->images->map(fn ($image) => [
                'id' => $image->id,
                'url' => asset('storage/'.$image->image_path),
                'sort_order' => $image->sort_order,
            ])),

            // Herbal/Ayurvedic metadata
            'benefits' => $this->benefits,
            'ingredients' => $this->ingredients,
            'usage_instructions' => $this->usage_instructions,
            'storage_instructions' => $this->storage_instructions,
            'precautions' => $this->precautions,
            'ayurvedic_reference' => $this->ayurvedic_reference,
            'suitable_for' => $this->suitable_for,
            'disclaimer' => $this->disclaimer,

            'is_featured' => (bool) $this->is_featured,
            'is_best_seller' => (bool) $this->is_best_seller,
            'is_trending' => (bool) $this->is_trending,

            'category' => new CategoryResource($this->whenLoaded('category')),
            'brand' => new BrandResource($this->whenLoaded('brand')),
            'tax_rate' => $this->whenLoaded('tax', fn () => $this->tax ? (float) $this->tax->rate : 0),

            'rating_avg' => $this->when($this->relationLoaded('reviews'), fn () => $this->reviews->count()
                ? round((float) $this->reviews->avg('rating'), 1)
                : null),
            'reviews_count' => $this->when($this->relationLoaded('reviews'), fn () => $this->reviews->count()),
            'reviews' => ReviewResource::collection($this->whenLoaded('reviews')),

            // SEO
            'meta_title' => $this->meta_title,
            'meta_description' => $this->meta_description,

            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
