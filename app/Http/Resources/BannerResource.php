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
            'desktop_image' => $this->desktop_image ? asset('storage/'.$this->desktop_image) : null,
            'mobile_image' => $this->mobile_image ? asset('storage/'.$this->mobile_image) : null,
            'link' => $this->link,
            'sort_order' => $this->sort_order,
        ];
    }
}
