<?php

namespace App\Support\Cache;

/**
 * Single source of truth for every storefront cache key + its TTL.
 *
 * Only public, non-personalized catalog/CMS data lives here (categories,
 * brands, taxes, banners, homepage rails, settings, static pages). Anything
 * user-specific (cart, wishlist, orders, checkout) is intentionally never
 * cached — see CacheService docblock.
 */
final class CacheKeys
{
    /** Combined homepage payload: banners, homepage categories, product rails, testimonials, blogs. */
    public const HOMEPAGE_DATA = 'homepage_data';

    /** Top-level categories (max 5) + children, used by the sitewide header nav. */
    public const HEADER_CATEGORIES = 'header_categories';

    /** Active CMS pages, used by the sitewide footer links. */
    public const FOOTER_PAGES = 'footer_pages';

    /** The single settings row (site name, contact info, SMTP, Razorpay keys, shipping/tax defaults, ...). */
    public const GLOBAL_SETTINGS = 'global_settings';

    /** Flat list of active categories, used by shop/API filter dropdowns. */
    public const SHOP_CATEGORIES = 'shop_categories';

    /** Top-level active categories + active children, used by the public category API. */
    public const CATEGORY_TREE = 'category_tree';

    /** Flat list of active brands, used by shop/API filter dropdowns. */
    public const ACTIVE_BRANDS = 'active_brands';

    /** Flat list of active tax slabs, used by admin product create/edit forms. */
    public const ACTIVE_TAXES = 'active_taxes';

    /** Standard TTL (seconds) for listing/filter caches that don't need "forever" semantics. */
    public const TTL_STANDARD = 3600;
}
