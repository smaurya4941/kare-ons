<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PageResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'content' => $this->content,
            // seo_title/seo_description are the current fields; meta_title/
            // meta_description are kept as deprecated aliases.
            'seo_title' => $this->seo_title,
            'seo_description' => $this->seo_description,
            'is_indexable' => (bool) $this->is_indexable,
            'meta_title' => $this->seo_title,
            'meta_description' => $this->seo_description,
        ];
    }
}
