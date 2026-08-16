<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\BrandResource;
use App\Models\Brand;
use App\Support\Cache\CacheKeys;
use Illuminate\Support\Facades\Cache;

class BrandController extends Controller
{
    public function index()
    {
        $brands = Cache::remember(CacheKeys::ACTIVE_BRANDS, CacheKeys::TTL_STANDARD, function () {
            return Brand::where('status', 1)->orderBy('name')->get();
        });

        return BrandResource::collection($brands);
    }
}
