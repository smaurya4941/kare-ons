<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class ProductIndexRequest extends FormRequest
{
    /** Allowed sort keys — prevents arbitrary ORDER BY injection. Mirrors Web\ShopController. */
    public const SORT_OPTIONS = [
        'latest' => ['created_at', 'desc'],
        'price_low' => ['price', 'asc'],
        'price_high' => ['price', 'desc'],
        'name_asc' => ['name', 'asc'],
        'name_desc' => ['name', 'desc'],
    ];

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'search' => 'nullable|string|max:100',
            'category' => 'nullable|string|max:100',
            'brand' => 'nullable|string|max:100',
            'min_price' => 'nullable|numeric|min:0|max:100000',
            'max_price' => 'nullable|numeric|min:0|max:100000|gte:min_price',
            'sort' => 'nullable|string|in:'.implode(',', array_keys(self::SORT_OPTIONS)),
            'per_page' => 'nullable|integer|min:1|max:48',
        ];
    }
}
