<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BannerResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'type' => $this->type,
            'desktop_image' => image_url($this->desktop_image),
            'mobile_image' => image_url($this->mobile_image),
            'link' => $this->link,
            'sort_order' => $this->sort_order,
        ];
    }
}
