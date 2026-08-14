<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'order_number' => $this->order_number,
            'subtotal' => (float) $this->subtotal,
            'shipping_charge' => (float) $this->shipping_charge,
            'discount_amount' => (float) $this->discount_amount,
            'tax_amount' => (float) $this->tax_amount,
            'grand_total' => (float) $this->grand_total,
            'payment_method' => $this->payment_method,
            'payment_status' => $this->payment_status,
            'order_status' => $this->order_status,
            'refund_status' => $this->refund_status,
            'notes' => $this->notes,
            'address' => new AddressResource($this->whenLoaded('address')),
            'items' => OrderItemResource::collection($this->whenLoaded('items')),
            'items_count' => $this->whenCounted('items'),
            'timelines' => OrderTimelineResource::collection($this->whenLoaded('timelines')),
            'return_requests' => ReturnRequestResource::collection($this->whenLoaded('returnRequests')),
            // Set on the model by Api\V1\OrderController@show only; null on list responses.
            'can_request_return' => $this->can_request_return === null ? null : (bool) $this->can_request_return,
            'return_window_days' => $this->return_window_days === null ? null : (int) $this->return_window_days,
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
