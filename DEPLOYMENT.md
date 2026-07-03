# Deployment Guide — Kare ONS Herbals

A step-by-step checklist to take this Laravel 12 storefront live so customers can
buy products. Follow in order.

## 0. Requirements
- PHP 8.2+ with extensions: `pdo`, `mbstring`, `openssl`, `gd`/`imagick`, `zip`, `bcmath`, `intl`
- Composer, Node.js 18+ (for building assets)
- MySQL 8 / MariaDB (or Postgres). **Do not use sqlite in production.**
- A web server (Nginx/Apache) with the docroot pointing at `public/`
- HTTPS certificate (required for Razorpay and secure cookies)

## 1. Code & dependencies
```bash
git clone <repo> && cd kare-ons
composer install --no-dev --optimize-autoloader
npm ci && npm run build
```

## 2. Environment
```bash
cp .env.production.example .env
php artisan key:generate
```
Then edit `.env`: set `APP_URL`, database credentials, and SMTP mail settings.
Confirm `APP_ENV=production` and `APP_DEBUG=false`.

## 3. Database
```bash
php artisan migrate --force
php artisan db:seed --force   # admin user, categories, products, payment methods, default shipping zone
```
The seeder creates an admin: **admin@kareons.com / password123** —
**log in and change this password immediately** (Admin → Profile).

## 4. Storage & permissions
```bash
php artisan storage:link
chmod -R ug+rw storage bootstrap/cache
```
(The symlink is environment-specific; it must be created on each server.)

## 5. Production caches
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```
Re-run these after any `.env` or code change.

## 6. Background workers (REQUIRED)
Order confirmation / shipping emails are **queued**, and abandoned-payment cleanup
is **scheduled**. Both need a running process or they silently do nothing.

**Queue worker** (via Supervisor / systemd):
```bash
php artisan queue:work --tries=3 --timeout=90
```

**Scheduler** (single crontab line):
```
* * * * * cd /path/to/kare-ons && php artisan schedule:run >> /dev/null 2>&1
```

## 7. Payments (Razorpay)
1. Admin → Settings → Payment: enter **Key ID**, **Key Secret**, **Webhook Secret**.
2. In the Razorpay dashboard, add a webhook:
   - URL: `https://your-domain.com/webhooks/razorpay`
   - Events: `payment.captured`, `payment.failed`, `order.paid`
   - Secret: must match the Webhook Secret above.
3. Test with a ₹1 live order, then refund it.

COD works out of the box with no configuration — the store can take orders on day one.

## 8. Go-live smoke test
- [ ] Home, Shop, Product, About, Blog, Contact pages load
- [ ] Register → login works
- [ ] Add to cart → checkout → place a **COD** order → order appears in Admin → Orders
- [ ] Order confirmation email received (confirms queue + mail work)
- [ ] Place a **Razorpay** order end-to-end; verify webhook marks it paid
- [ ] Admin can update order status; cancelling restocks inventory

## Default admin
`admin@kareons.com` / `password123` — change on first login.
