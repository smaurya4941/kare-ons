<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $product = $this->product;
        $effectivePrice = $product ? (float) ($product->sale_price ?? $product->price) : 0;

        return [
            'id' => $this->id,
            'quantity' => (int) $this->quantity,
            'unit_price' => $effectivePrice,
            'line_total' => round($effectivePrice * $this->quantity, 2),
            'product' => $product ? [
                'id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'main_image' => $product->main_image ? asset('storage/'.$product->main_image) : null,
                'price' => (float) $product->price,
                'sale_price' => $product->sale_price !== null ? (float) $product->sale_price : null,
                'stock_quantity' => (int) $product->stock_quantity,
                'status' => (bool) $product->status,
            ] : null,
        ];
    }
}
