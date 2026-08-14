# Kare Ons Customer API (v1)

A standalone REST API for customer-facing features, for any future headless
frontend to consume. This app is not currently wired to any separate
frontend — the Blade storefront below is the live customer experience. The
**admin panel stays on
Laravel Blade** (`routes/web.php`, `App\Http\Controllers\Admin\*`) and is not
part of this API. The existing Blade storefront (`routes/web.php`,
`App\Http\Controllers\Web\*`) also keeps working unchanged; this API is an
additional, independent surface that shares the same database and the same
`CheckoutService`/`CouponService` business logic.

Base URL: `{APP_URL}/api/v1` (e.g. `https://api.kareons.com/api/v1`).

## Auth

Bearer tokens via [Laravel Sanctum](https://laravel.com/docs/sanctum) — **no
cookies, no CSRF token, no shared domain required.** Every authenticated
route requires:

```
Authorization: Bearer <token>
Accept: application/json
```

Get a token from `POST /auth/register` or `POST /auth/login`. Tokens expire
after `SANCTUM_TOKEN_EXPIRATION` minutes (default 43200 = 30 days; see
`.env`). There is no refresh-token flow — re-authenticate with
`/auth/login` once a token expires or is revoked.

**No guest cart.** Unlike the Blade storefront, `/cart`, `/wishlist`,
`/checkout`, `/orders`, `/addresses`, and `/profile` all require a token —
there is no session-based guest cart in the API. Build the frontend's cart
as "log in (or register) before adding to cart," or persist an in-progress
cart client-side until the user authenticates.

## Response shapes

- Single resource: `{ "data": { ... } }`
- Collection: `{ "data": [ ... ] }`, paginated collections also include
  Laravel's standard `"links"` and `"meta"` (page, per_page, total, etc).
- Errors: `{ "message": "..." }`, validation errors add
  `{ "message": "...", "errors": { "field": ["..."] } }` with HTTP 422.
- Auth failure → 401. Ownership/authorization failure → 403. Not found →
  404. Rate-limited → 429. Unexpected server error → 500. All as JSON,
  never a Blade error page (see `bootstrap/app.php` → `withExceptions`).

## Rate limits

| Limiter | Scope | Limit |
|---|---|---|
| `api` (default, all routes) | per user/IP | 60/min |
| `search` (`/search/suggest`) | per user/IP | 60/min |
| `coupon` (`/coupons/validate`) | per IP | 10/min |
| `reviews` (`/products/{id}/reviews`) | per user | 5/hour |
| login/register lockout | per email+IP | 5 attempts, then locked (seconds shown in error) |
| email verify/resend | per IP | 6/min |

---

## Public endpoints

### Content & catalog

| Method | Path | Notes |
|---|---|---|
| GET | `/home` | Banners, homepage categories, featured/best-seller/trending/new-arrival rails, testimonials, latest 3 blog posts, `wishlist_ids` (empty unless a valid Bearer token is sent — see below). Cached server-side for 1 hour. |
| GET | `/settings` | Public site settings: name, contact, social links, shipping charge / free-shipping threshold, public `razorpay_key`, SEO defaults. Never includes secrets. |
| GET | `/categories` | Top-level categories with nested `children`. |
| GET | `/categories/{slug}` | Single category + its children. |
| GET | `/brands` | Active brands. |
| GET | `/products` | Shop listing. Query: `search`, `category` (slug), `brand` (slug), `min_price`, `max_price`, `sort` (`latest`\|`price_low`\|`price_high`\|`name_asc`\|`name_desc`), `per_page` (max 48, default 12). Paginated. `meta.categories` included for building a filter sidebar. |
| GET | `/products/{slug}` | Full product detail + `related_products` (4, same category). |
| GET | `/search/suggest?q=` | Live autocomplete, min 2 chars, max 6 results. |
| GET | `/blog` | Paginated posts + `meta.categories`. |
| GET | `/blog/{slug}` | Post detail + `related_blogs`. |
| GET | `/pages/{slug}` | CMS page (privacy, terms, faq, about, ...). |
| POST | `/contact` | Body: `name`, `email`, `subject?`, `message`. Creates a `ContactInquiry` and notifies admins. |
| POST | `/coupons/validate` | Body: `code`, `subtotal`. Optionally authenticated — send a Bearer token to also enforce the one-time-per-user usage check. Returns `{ data: { code, type, discount } }` or 422 with a human-readable `message`. |

**Optional auth on `/products*` and `/home`:** these routes have no
`auth:sanctum` middleware, but if a valid Bearer token is sent, product
payloads include an `in_wishlist` boolean per item (via
`$request->user('sanctum')` — safe to call on any route, middleware or not).

### Auth

| Method | Path | Body | Notes |
|---|---|---|---|
| POST | `/auth/register` | `name`, `email`, `phone`, `password`, `password_confirmation`, `device_name?` | Creates a `customer`-role user, sends a verification email, returns `{ data: { user, token, token_type } }` (201). |
| POST | `/auth/login` | `email`, `password`, `device_name?` | Same 5-attempt lockout as the Blade login. Returns `{ data: { user, token, token_type } }`. |
| POST | `/auth/logout` 🔒 | — | Revokes only the token used for this request. |
| GET | `/auth/user` 🔒 | — | Current user. |
| POST | `/auth/forgot-password` | `email` | Sends a reset email whose link points at `{FRONTEND_URL}/reset-password?token=...&email=...` (the frontend owns this page). |
| POST | `/auth/reset-password` | `token`, `email`, `password`, `password_confirmation` | Completes the reset. |
| GET | `/auth/verify-email/{id}/{hash}` | — | The link opened directly from the verification email (signed URL, no token needed). Redirects the browser to `{FRONTEND_URL}/email-verified?status=success\|invalid`. |
| POST | `/auth/email/resend` 🔒 | — | Resends the verification email if not yet verified. |

🔒 = requires `Authorization: Bearer <token>`.

---

## Authenticated customer endpoints 🔒

All require a Bearer token.

### Profile

| Method | Path | Body |
|---|---|---|
| GET | `/profile` | — |
| PATCH | `/profile` | `name`, `phone`, `email` (changing email clears verification — resend via `/auth/email/resend`) |
| PUT | `/profile/password` | `current_password`, `password`, `password_confirmation` |
| DELETE | `/profile` | `password` — deletes the account and revokes all tokens |

### Addresses

Standard REST resource, no `show`: `GET /addresses`, `POST /addresses`,
`PUT /addresses/{id}`, `DELETE /addresses/{id}`.
Body: `full_name`, `phone`, `address_line_1`, `address_line_2?`, `city`,
`state`, `postal_code`, `is_default?` (bool). The first address a user
creates is always forced default; setting `is_default` on any address
unsets it on the others; deleting the default promotes the next-latest
address automatically.

### Cart

| Method | Path | Body |
|---|---|---|
| GET | `/cart` | — → `{ data: { items, subtotal, shipping, total, cart_count } }` |
| POST | `/cart` | `product_id`, `quantity` (1–10; adds to existing line, capped at 10) |
| PUT | `/cart/{cartItem}` | `quantity` |
| DELETE | `/cart/{cartItem}` | — |

### Wishlist

`GET /wishlist`, `POST /wishlist/{product}` (toggle — adds or removes, returns
`{ status: 'added'|'removed', wishlist_count }`), `DELETE /wishlist/{product}`.

### Reviews

`POST /products/{product}/reviews` — body: `rating` (1–5), `title?`,
`comment` (10–2000 chars). One review per user per product (409 if a review
already exists). New reviews are always created pending moderation
(`status: false` — hidden from `/products/{slug}` until an admin approves
them in the Blade admin panel).

### Checkout

| Method | Path | Notes |
|---|---|---|
| GET | `/checkout/summary` | Cart items, subtotal, tax, shipping, total, saved addresses, active payment methods. |
| POST | `/checkout` | Body: `full_name`, `phone`, `address_line_1`, `address_line_2?`, `city`, `state`, `postal_code`, `payment_method` (a code from `/checkout/summary`'s `payment_methods`), `coupon_code?`. **Always creates a new `Address` row** (does not reuse a saved address id — matches the Blade behavior). Returns `{ data: { order, razorpay } }`; `razorpay` is `null` for COD (order is placed immediately, `OrderPlaced` email sent), or `{ key, order_id, amount, currency }` for Razorpay — pass these straight into Razorpay Checkout.js. 409 if stock ran out, 422 for an empty cart or invalid coupon. |
| POST | `/checkout/verify-payment` | Body: `razorpay_order_id`, `razorpay_payment_id`, `razorpay_signature` (from the Razorpay Checkout.js success callback). Verifies the signature and confirms the order — idempotent, safe even if the server-to-server webhook (`POST /webhooks/razorpay`, unauthenticated, unrelated to this API) already confirmed it. |

Stock is decremented and the inventory ledger is written **inside** order
creation (before payment, under a row lock) — a failed/abandoned Razorpay
payment automatically restocks and cancels the order (see
`RazorpayPaymentService::markFailed`).

### Orders & returns

| Method | Path | Notes |
|---|---|---|
| GET | `/orders` | Paginated, newest first. |
| GET | `/orders/{order}` | Includes `items`, `address`, `timelines`, `return_requests`, and computed `can_request_return` / `return_window_days`. |
| POST | `/orders/{order}/return` | Body: `type` (`refund`\|`replacement`), `reason`, `customer_note?`. Only allowed for `delivered` orders, within 7 days of the delivered timeline entry, and only if no pending/approved/completed return already exists for that order. |

---

## Example: register → add to cart → checkout (COD)

```
POST /api/v1/auth/register
{ "name": "Asha Rao", "email": "asha@example.com", "phone": "9876543210",
  "password": "secret123", "password_confirmation": "secret123" }
→ { "data": { "user": {...}, "token": "1|xxxx", "token_type": "Bearer" } }

POST /api/v1/cart
Authorization: Bearer 1|xxxx
{ "product_id": 12, "quantity": 2 }

POST /api/v1/checkout
Authorization: Bearer 1|xxxx
{ "full_name": "Asha Rao", "phone": "9876543210",
  "address_line_1": "12 MG Road", "city": "Bengaluru", "state": "Karnataka",
  "postal_code": "560001", "payment_method": "cod" }
→ { "data": { "order": { "order_number": "KO-AB12CD34", ... }, "razorpay": null } }

GET /api/v1/orders/{id}
Authorization: Bearer 1|xxxx
```

## Notes for a future headless frontend integration

No frontend is wired to this API today; `FRONTEND_URL` is unset by default,
so CORS is open (`*`) and the links below fall back to `APP_URL`. When a
frontend is actually deployed against this API:

- Store the token in an httpOnly cookie set by your own frontend's API route,
  or in memory + refreshed from a secure store — **not** `localStorage` if
  you can avoid it (XSS risk), though `localStorage` is the pragmatic
  default for a first pass.
- Set `FRONTEND_URL` (see `config/cors.php`) to that frontend's origin(s) to
  restrict CORS, per environment (local, staging, prod).
- Image URLs returned by resources (`main_image`, `logo`, `desktop_image`,
  etc.) are absolute (`asset('storage/...')`) — use them directly, no need
  to prefix with the API host separately... they already include it.
- The email-verification and password-reset links in customer emails open
  in a browser, not the app — once `FRONTEND_URL` is set they redirect to
  `{FRONTEND_URL}/email-verified` and `{FRONTEND_URL}/reset-password`
  respectively. Build those two pages in that frontend.
