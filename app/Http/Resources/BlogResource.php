<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BlogResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'category' => $this->category,
            'excerpt' => $this->excerpt,
            'content' => $this->content,
            'featured_image' => image_url($this->featured_image),
            'author' => $this->whenLoaded('author', fn () => $this->author ? ['name' => $this->author->name] : null),
            // seo_title/seo_description are the current fields; meta_title/
            // meta_description are kept as deprecated aliases.
            'seo_title' => $this->seo_title,
            'seo_description' => $this->seo_description,
            'is_indexable' => (bool) $this->is_indexable,
            'meta_title' => $this->seo_title,
            'meta_description' => $this->seo_description,
            'published_at' => $this->published_at?->toIso8601String(),
        ];
    }
}
