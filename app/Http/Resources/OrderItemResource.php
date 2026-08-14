<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'product_id' => $this->product_id,
            'product_name' => $this->product_name,
            'sku' => $this->sku,
            'price' => (float) $this->price,
            'quantity' => (int) $this->quantity,
            'total' => (float) $this->total,
            'product' => $this->whenLoaded('product', fn () => $this->product ? [
                'slug' => $this->product->slug,
                'main_image' => image_url($this->product->main_image),
            ] : null),
        ];
    }
}
