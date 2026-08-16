<?php

namespace App\Services;

use App\Support\Cache\CacheKeys;
use Illuminate\Support\Facades\Cache;

/**
 * Central cache-invalidation surface for public storefront/catalog data.
 *
 * Every model whose data feeds a cached read (Category, Product, Brand, Tax,
 * Banner, Blog, Testimonial, Page, Setting) calls the matching flush*()
 * method from its own booted() hook on save/delete. Keeping the "what to
 * bust" logic here — instead of scattered across controllers — means a
 * write can never forget to invalidate a cache that reads the same table.
 *
 * Deliberately NOT cached here: cart, checkout, orders, addresses,
 * inventory, wishlist. Those are per-user or change on every request, so
 * caching them risks serving stale stock/price/order-status data.
 */
class CacheService
{
    public static function flushCategories(): void
    {
        Cache::forget(CacheKeys::HOMEPAGE_DATA);
        Cache::forget(CacheKeys::HEADER_CATEGORIES);
        Cache::forget(CacheKeys::SHOP_CATEGORIES);
        Cache::forget(CacheKeys::CATEGORY_TREE);
    }

    public static function flushProducts(): void
    {
        // Product listings themselves (shop/API index) are paginated + filtered
        // per request and are never cached directly — only the homepage rails
        // (featured/best-seller/trending/new-arrivals) pull from cache.
        Cache::forget(CacheKeys::HOMEPAGE_DATA);
    }

    public static function flushBrands(): void
    {
        Cache::forget(CacheKeys::ACTIVE_BRANDS);
    }

    public static function flushTaxes(): void
    {
        Cache::forget(CacheKeys::ACTIVE_TAXES);
    }

    public static function flushBanners(): void
    {
        Cache::forget(CacheKeys::HOMEPAGE_DATA);
    }

    public static function flushBlogs(): void
    {
        Cache::forget(CacheKeys::HOMEPAGE_DATA);
    }

    public static function flushTestimonials(): void
    {
        Cache::forget(CacheKeys::HOMEPAGE_DATA);
    }

    public static function flushPages(): void
    {
        Cache::forget(CacheKeys::FOOTER_PAGES);
    }

    public static function flushSettings(): void
    {
        Cache::forget(CacheKeys::GLOBAL_SETTINGS);
        // Settings drive shipping/free-shipping thresholds shown on the homepage
        // and the CTA/hero content baked into the homepage payload.
        Cache::forget(CacheKeys::HOMEPAGE_DATA);
    }
}
