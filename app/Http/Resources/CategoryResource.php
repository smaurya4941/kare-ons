<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'image' => image_url($this->image),
            'banner_image' => image_url($this->banner_image),
            'parent_id' => $this->parent_id,
            'sort_order' => $this->sort_order,
            'children' => CategoryResource::collection($this->whenLoaded('children')),

            // SEO — seo_title/seo_description are the current fields; meta_title/
            // meta_description are kept as deprecated aliases for API consumers
            // built against the old field names and will be removed later.
            'seo_title' => $this->seo_title,
            'seo_description' => $this->seo_description,
            'is_indexable' => (bool) $this->is_indexable,
            'meta_title' => $this->seo_title,
            'meta_description' => $this->seo_description,
        ];
    }
}
