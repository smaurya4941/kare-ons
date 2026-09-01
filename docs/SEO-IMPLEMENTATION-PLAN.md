# SEO Implementation Plan — kare-ons

**Status: Phases 1–7 implemented in code (2026-08-29).** Not yet run against a live DB — see "Deployment steps" at the bottom of this file for what to run on first deploy. Migration 1.7 (dropping the legacy `meta_title`/`meta_description` columns) is intentionally NOT created yet — create and run it only after confirming in production that the new `seo_title`/`seo_description` fields are populated and working.

**Domain:** `kareonsherbal.com` (hPanel/Hostinger shared hosting — Apache/LiteSpeed, TLS terminated directly on the server, no reverse proxy/load balancer in front, so no `TrustProxies`/`X-Forwarded-Proto` configuration is needed).

## Context

A prior audit against a 60-point SEO checklist found the technical SEO foundation (component structure, sitemap, robots.txt, slugs, caching, indexes) already well-built (~75% there). Follow-up exploration surfaced a **live, silent bug**: the admin panel (Product/Category/Blog/Page edit forms) validates and saves `meta_title`/`meta_description`, while every storefront view reads `seo_title`/`seo_description`/`is_indexable` instead. **Admin edits to "Meta Title"/"Meta Description" currently have zero effect on what Google sees.** Fixing that data-layer mismatch is the highest-priority item — everything else layers on top of it. The remaining gaps are consistency gaps (patterns that exist on the product page but weren't extended to blog/cart/checkout/account pages), plus genuinely missing pieces (redirects, custom error pages, HTTPS enforcement, GSC verification).

Decisions locked in with the user:
- Old `meta_title`/`meta_description` columns: **two-step** — backfill into `seo_*` fields and cut the admin panel over first; drop the old columns in a separate follow-up migration once verified.
- Custom 404 page: **enriched** (branded + featured/popular products, not just a bare message).
- 301 redirects: **DB table only**, no admin UI for now (seed via tinker/DB as needed).
- Production domain: `kareonsherbal.com`, hPanel shared hosting — TLS terminates on the server itself, so HTTPS enforcement is `URL::forceScheme('https')` gated to production, no trusted-proxy config needed.

---

## Phase 1 — Data Layer (fixes the live bug)

### 1.1 Migration A — additive: SEO parity + backfill
New migration `..._add_seo_parity_and_backfill.php`:
- Add `seo_title` (string, nullable), `seo_description` (text, nullable), `is_indexable` (boolean, default true) to `blogs` and `pages` tables (products/categories already have these from `2026_08_29_140000_add_seo_fields_to_products_and_categories_tables.php`).
- Backfill via `DB::table(...)->update()` (not Eloquent, to skip model events) for all four tables: copy `meta_title` → `seo_title`, `meta_description` → `seo_description` wherever the new column is null and the old one isn't.

### 1.2 Fix broken rollback
`database/migrations/2026_08_29_140000_add_seo_fields_to_products_and_categories_tables.php:32` — `down()` targets a nonexistent table `products_and_categories_tables`. Fix to two real `Schema::table('products', ...)`/`Schema::table('categories', ...)` blocks dropping the three columns. Safe, standalone, do immediately.

### 1.3 Admin controllers — validate/save the NEW fields
Files: `app/Http/Controllers/Admin/ProductController.php` (~83-84, ~194-195), `CategoryController.php` (~44-45, ~90-91), `BlogController.php` (~49-50, ~95-96), `PageController.php` (~28-29, ~52-53).
Replace `meta_title`/`meta_description` validation rules with:
```php
'seo_title'       => 'nullable|string|max:255',
'seo_description' => 'nullable|string|max:500',
'is_indexable'    => 'boolean',
```
Add `$validated['is_indexable'] = $request->boolean('is_indexable', true);` next to existing boolean-cast lines (Product/Category controllers already do this for `featured`/etc. — follow that exact pattern).

### 1.4 Admin Blade forms — rename fields, add indexable checkbox
Worked example (`resources/views/admin/products/edit.blade.php:159-167`):
```blade
<label for="seo_title">SEO Title</label>
<input type="text" name="seo_title" id="seo_title" value="{{ old('seo_title', $product->seo_title) }}">
<label for="seo_description">SEO Description</label>
<textarea name="seo_description" id="seo_description" rows="2">{{ old('seo_description', $product->seo_description) }}</textarea>
<label class="inline-flex items-center gap-2">
    <input type="checkbox" name="is_indexable" value="1" {{ old('is_indexable', $product->is_indexable ?? true) ? 'checked' : '' }}>
    Allow search engines to index this product
</label>
```
Apply identically to: `admin/products/create.blade.php`, `admin/categories/edit.blade.php` + `create.blade.php`, `admin/blogs/_form.blade.php`, `admin/pages/form.blade.php`.
(`admin/settings/tabs/seo.blade.php` is site-wide defaults via `setting()` — separate concern, no change.)

### 1.5 Models — fillable/casts/scopes
- `app/Models/Blog.php`: add `seo_title`, `seo_description`, `is_indexable` to `$fillable`; add `is_indexable` boolean cast; extend/add a scope that checks both `published()` and `is_indexable`.
- `app/Models/Page.php`: same three fields + boolean cast + new `scopeIndexable()`.
- `app/Models/Product.php` / `Category.php`: already `$guarded=['id']` with `is_indexable` cast — just add:
```php
public function scopeIndexable($query) {
    return $query->where('status', true)->where('is_indexable', true);
}
```
- `Product.php` `$activityLogIgnore`: drop `meta_description` once the column is gone (Phase 1.7).

### 1.6 Seeders & API Resources
- `database/seeders/PageSeeder.php`: add distinct `seo_title`/`seo_description` per seeded page (Privacy Policy, Refund Policy, Shipping Policy, Terms, FAQ) — currently only `title`/`content`/`status` are set.
- Grep `ProductSeeder.php`/`CategorySeeder.php`/`BlogSeeder.php` for `meta_title`/`meta_description` and update if present.
- `app/Http/Resources/ProductResource.php`, `BlogResource.php`, `PageResource.php`: add `seo_title`/`seo_description`/`is_indexable` keys; keep old keys returning the same underlying value for now (transitional compatibility) until confirmed nothing external depends on them.

### 1.7 Migration B — destructive: drop old columns (separate PR/deploy)
After 1.1–1.6 are deployed and verified in production (admin edits confirmed reflected on storefront), add a follow-up migration dropping `meta_title`/`meta_description` from `products` and `categories` (blogs/pages never had them duplicated — n/a there once 1.1 lands... actually blogs/pages keep `meta_title`/`meta_description` as legacy too if anything still reads them; confirm no lingering reads before dropping, same as products/categories).

---

## Phase 2 — Per-Page Meta

### 2.1 `resources/views/pages/about.blade.php`
Add `@section('title', 'About Us')` and `@section('meta_description', '...')` — currently has neither, falls back to generic site defaults.

### 2.2 `resources/views/pages/show.blade.php`
Currently: `@section('title', $page->title . ' - ' . setting('site_name', ...))` — never touches `$page->meta_title`/`meta_description`. Change to:
```blade
@section('title', $page->seo_title ?: $page->title)
@section('meta_description', $page->seo_description ?: \Illuminate\Support\Str::limit(strip_tags($page->content), 160))
@if(isset($page->is_indexable) && !$page->is_indexable)
    @section('no_index', 'true')
@endif
```
Drop the manual `' - ' . setting('site_name', ...)` concatenation — `<x-seo>` already appends the site name.

### 2.3 The dropped account-page title/description (wishlist, orders, profile, addresses)
These pages use `<x-customer-layout>` with `<x-slot name="title">`, which only feeds the visible on-page `<h2>` — it never reaches `<title>`/meta description, which are sourced purely from `@section('title'/'meta_description')`. Fix: keep the `x-slot` as-is (it's doing its job for the H2), and additionally add standard `@section` calls at the top of each file, matching every other view in the app. Worked example (`resources/views/wishlist/index.blade.php`):
```blade
@section('title', 'My Wishlist')
@section('meta_description', 'View and manage the Ayurvedic products you have saved to your wishlist.')
@section('no_index', 'true')

<x-customer-layout>
    <x-slot name="title">My Wishlist</x-slot>
    ...
```
Apply the same 3-line pattern (page-appropriate title/description, always `no_index = true` since these are private account pages) to: `orders/index.blade.php`, `orders/show.blade.php`, `profile/edit.blade.php`, `addresses/index.blade.php`, `addresses/create.blade.php`, `addresses/edit.blade.php`.

### 2.4 Cart / Checkout
Add `meta_description` + `no_index` to `resources/views/cart/index.blade.php` and `checkout/index.blade.php` (currently title-only):
```blade
@section('title', 'Your Cart')
@section('meta_description', 'Review the items in your shopping cart before checkout.')
@section('no_index', 'true')
```

### 2.5 `resources/views/blog/index.blade.php`
Add the missing `@section('meta_description', 'Explore Ayurvedic wellness articles, herbal remedies, and holistic health tips from Kare Ons Herbal.')`.

### 2.6 `resources/views/blog/show.blade.php`
Switch from `meta_title`/`meta_description` to `seo_title`/`seo_description` (same fallback pattern as elsewhere), and add:
```blade
@if(isset($blog->is_indexable) && !$blog->is_indexable)
    @section('no_index', 'true')
@endif
```

---

## Phase 3 — Structured Data (JSON-LD)

Pattern already established: `@push('schema')` blocks rendered via `@stack('schema')` in `layouts/app.blade.php:48` (in `<head>`). Reference: `product/show.blade.php:546-603`.

1. **AggregateRating/Review** — extend the existing Product JSON-LD in `product/show.blade.php`, only emitted `@if($product->reviews->count() > 0)`, using up to 5 reviews. Confirm the `Review` model's rating column name before writing.
2. **BlogPosting schema** — new `@push('schema')` block on `blog/show.blade.php` (headline, description, image, datePublished/dateModified, author, publisher).
3. **BreadcrumbList schema** on `blog/show.blade.php` (visible breadcrumb already exists at lines 11-19, just missing the matching JSON-LD — copy the pattern from `product/show.blade.php`) and on `shop/index.blade.php` (add both the visible breadcrumb nav and its schema, since neither currently exists there).
4. **ItemList/CollectionPage schema** on `shop/index.blade.php` for the current page of products only (12 items per `paginate(12)`).
5. **FAQPage schema** — skip; no structured Q&A content exists yet (the FAQ page is one HTML blob).

---

## Phase 4 — Sitemap & Robots

### 4.1 `SitemapController.php`
Replace `Product::where('status', true)` / etc. with the new `->indexable()` scopes from Phase 1.5, and add a `Category::indexable()->get()` query, passed to the view.

### 4.2 `resources/views/sitemap/index.blade.php`
Add a `@foreach($categories as $category)` loop (verify the actual shop category route/param first) and `<image:image>` tags (via the `xmlns:image` namespace on `<urlset>`) for product `main_image` where non-null.

### 4.3 `routes/web.php` robots.txt closure (~lines 46-61)
Add `'Disallow: /addresses',` — currently missing alongside the existing 6 disallow entries.

---

## Phase 5 — Redirects / 404 / HTTPS

### 5.1 301 redirects (table only, no admin UI)
- Migration: `redirects` table — `from_path` (unique, indexed), `to_path`, `status_code` (default 301), timestamps.
- `Redirect` model.
- `Route::fallback(...)` in `routes/web.php` (after the catch-all CMS route) or a small early middleware that checks the table before falling through to 404. Seed/manage entries manually via `php artisan tinker` as slugs change.

### 5.2 Enriched 404 page
`resources/views/errors/404.blade.php`, extending `layouts.app`, `no_index`, branded message, plus a "Popular Products" or "Featured Products" section (reuse the existing featured-products query pattern from `HomeController.php`) and links to top categories, so lost visitors have somewhere to go instead of a dead end. Also add `resources/views/errors/500.blade.php` (minimal, branded, no product query — must stay resilient if the DB itself is the problem).

### 5.3 HTTPS enforcement
Since hPanel/Hostinger shared hosting terminates TLS directly on the server (no reverse proxy in front), no `TrustProxies` config is needed. Two-part fix:
1. `app/Providers/AppServiceProvider.php` `boot()` — add:
```php
if (app()->environment('production')) {
    URL::forceScheme('https');
}
```
2. Add an `.htaccess` rule (Apache, standard on hPanel) in `public/.htaccess` to redirect `http://` → `https://` and (decide) `www` → non-`www` or vice versa for `kareonsherbal.com`, at the edge before Laravel even boots — cheaper and more reliable than an app-level redirect on shared hosting.
3. Set the real production `.env`: `APP_URL=https://kareonsherbal.com`, `APP_ENV=production`, `APP_DEBUG=false` (currently `APP_URL=http://localhost` in the checked-in `.env` — must be corrected on the actual server's environment file, not the repo's local `.env`).

### 5.4 Canonical normalization
Once 5.3 lands, `url()->current()` (used as the canonical fallback in `seo.blade.php`) will correctly emit `https://kareonsherbal.com/...`. The `.htaccess` www/non-www rule in 5.3 handles host normalization at the edge, so no further Blade-level change is needed.

---

## Phase 6 — Images / Semantic HTML / Pagination

### 6.1 Duplicate `<main>` landmark
Confirmed: `layouts/app.blade.php:207-210` already wraps `@yield('content')` and `$slot` in one `<main>`. Change the inner `<main>` tags in `product/show.blade.php:25` and `shop/index.blade.php:32` to `<div>` (keep all existing classes) — the outer layout already owns the single `<main>` landmark.

### 6.2 Lazy-loading consistency
Add `loading="lazy" decoding="async"` to `product/show.blade.php`'s thumbnail/gallery images (~lines 65, 69) and related-products images (~line 507) — matching the pattern already used in `shop/index.blade.php:271`. Leave the primary/active product image (~line 54, likely the LCP element) eager — optionally add `fetchpriority="high"` there instead.

### 6.3 `rel="prev"/"next"` pagination hints
Extend `resources/views/components/seo.blade.php` with `prevUrl`/`nextUrl` props rendering `<link rel="prev"/"next">`, wired through `layouts/app.blade.php`'s `<x-seo>` call via two new `@yield` sections. In `shop/index.blade.php` and `blog/index.blade.php`, near the pagination call, add:
```blade
@section('prev_url', $products->currentPage() > 1 ? $products->previousPageUrl() : null)
@section('next_url', $products->hasMorePages() ? $products->nextPageUrl() : null)
```

---

## Phase 7 — Google Search Console

Add a conditional verification tag in `layouts/app.blade.php`:
```blade
@if(setting('google_site_verification'))
<meta name="google-site-verification" content="{{ setting('google_site_verification') }}">
@endif
```
Expose `google_site_verification` as a field in `admin/settings/tabs/seo.blade.php` (same `setting()`-backed pattern already used there). The actual verification string must come from the user's Google Search Console account once `kareonsherbal.com` is added as a property there.

---

## Verification Plan

Run after Phase 1: `php artisan migrate`, `php artisan config:clear && php artisan view:clear && php artisan cache:clear` (SEO fields feed cached queries).

- **Phase 1 (critical regression check):** submit the admin product/category/blog/page edit forms with new SEO Title/Description → reload the public page → view-source → confirm `<title>`/`<meta name="description">` reflect the new values. Spot-check that old `meta_title`/`meta_description` data was correctly backfilled before Migration B (1.7) runs.
- **Phase 2:** view-source `/about`, a CMS page, `/wishlist`, `/profile`, `/orders`, `/addresses`, `/cart`, `/checkout`, `/blog`, a blog post — confirm page-specific title/description and `noindex` where expected. Toggle `is_indexable=false` on a test record via admin → confirm `noindex` appears only there.
- **Phase 3:** paste each JSON-LD block into a JSON validator (watch for unescaped quotes from user-generated review/blog content); spot-check with Google's Rich Results Test once staged/live.
- **Phase 4:** visit `/sitemap.xml` — well-formed XML, categories present, noindexed items absent. Visit `/robots.txt` — `Disallow: /addresses` present, `Sitemap:` line correct.
- **Phase 5:** visit a nonexistent URL → branded 404, noindexed. Seed a test redirect → confirm a real 301 (curl -I, not just visual redirect). In production: confirm `http://` → `https://` and canonical/OG tags emit `https://kareonsherbal.com`.
- **Phase 6:** validate `/shop` and a product page have exactly one `<main>` in the DOM; devtools confirm gallery/related images lazy-load while the primary image loads immediately; view-source `/shop?page=2` and `/blog?page=2` for `rel="prev"/"next"`.
- **Phase 7:** add the verification meta tag, then click "Verify" in Google Search Console (manual, external).

## Open follow-ups (not blocking, revisit later)
- FAQPage schema once the FAQ content is restructured into discrete Q&A pairs.
- ~~Confirm `Review` model's rating column name before writing Phase 3.1.~~ Confirmed `rating` (tinyInteger) — done.
- ~~Confirm the exact shop category route/query param before writing the sitemap category loop (Phase 4.2).~~ Confirmed `route('shop.index', ['category' => $slug])` — done.
- Migration 1.7 (drop legacy `meta_title`/`meta_description` from `products`/`categories`) — create once verified live.
- No admin UI for the `redirects` table yet (by design, per user decision) — add entries via `php artisan tinker`, e.g.:
  `Redirect::create(['from_path' => 'product/old-slug', 'to_path' => 'product/new-slug']);`
- Also fixed along the way (found during implementation, not in the original audit): the admin panel's global "Global Meta Description" setting (`seo_meta_description` column) was never actually read — `components/seo.blade.php` was calling `setting('meta_description', ...)`, a key that never existed. Now reads `setting('seo_meta_description', ...)`.

## Deployment steps (run once, on first deploy after this change set)
1. Ensure the server's real `.env` has `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL=https://kareonsherbal.com` (see `.env.production.example`).
2. `php artisan migrate` — applies: SEO field fix + backfill (products/categories), SEO parity for blogs/pages + backfill, `redirects` table, `google_site_verification` settings column.
3. `php artisan config:clear && php artisan view:clear && php artisan cache:clear` — SEO fields feed cached queries (homepage data, header categories, global settings).
4. Spot-check: edit a product's SEO Title/Description in `/admin/products/{id}/edit`, save, then view-source the public product page and confirm the new values appear in `<title>`/`<meta name="description">` — this is the regression check for the core bug this work fixed.
5. Visit `/sitemap.xml` and `/robots.txt` to confirm they render correctly with the new domain.
6. Once you have a Google Search Console verification token, paste it into Admin → Settings → SEO → "Google Search Console Verification".
