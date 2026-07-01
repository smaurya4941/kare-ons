<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\ProductController;

// ============================================================================
// Public Routes
// ============================================================================
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [\App\Http\Controllers\PageController::class, 'about'])->name('about');
Route::get('/contact', [\App\Http\Controllers\PageController::class, 'contact'])->name('contact');
Route::post('/contact', [\App\Http\Controllers\PageController::class, 'submitContact'])->name('contact.submit');
Route::get('/page/{slug}', [\App\Http\Controllers\PageController::class, 'show'])->name('page.show');
Route::get('/shop', [\App\Http\Controllers\Web\ShopController::class, 'index'])->name('shop.index');
Route::get('/product/{slug}', [ProductController::class, 'show'])->name('product.show');

// Cart Routes
Route::get('/cart', [\App\Http\Controllers\Web\CartController::class, 'index'])->name('cart.index');
Route::post('/cart', [\App\Http\Controllers\Web\CartController::class, 'store'])->name('cart.add');
Route::put('/cart/{cartItem}', [\App\Http\Controllers\Web\CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/{cartItem}', [\App\Http\Controllers\Web\CartController::class, 'destroy'])->name('cart.remove');



// Coupon (AJAX) Routes — accessible to guests too, rate-limited
Route::post('/coupon/apply', [\App\Http\Controllers\Web\CouponController::class, 'apply'])->name('coupon.apply')->middleware('throttle:coupon');
Route::post('/coupon/remove', [\App\Http\Controllers\Web\CouponController::class, 'remove'])->name('coupon.remove');

// Blog Routes
Route::get('/blog', [\App\Http\Controllers\Web\BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [\App\Http\Controllers\Web\BlogController::class, 'show'])->name('blog.show');

// Sitemap
Route::get('/sitemap.xml', [\App\Http\Controllers\Web\SitemapController::class, 'index'])->name('sitemap.index');

// robots.txt — served dynamically so the Sitemap URL matches the current domain
Route::get('/robots.txt', function () {
    $lines = [
        'User-agent: *',
        'Disallow: /admin/',
        'Disallow: /dashboard',
        'Disallow: /checkout',
        'Disallow: /cart',
        'Disallow: /orders',
        'Disallow: /profile',
        'Disallow: /wishlist',
        '',
        'Sitemap: ' . url('/sitemap.xml'),
    ];

    return response(implode("\n", $lines) . "\n", 200, ['Content-Type' => 'text/plain']);
})->name('robots');

// ============================================================================
// Authenticated Customer Routes
// ============================================================================
Route::middleware('auth')->group(function () {
    // Product Review — requires login, rate-limited to 5 per hour
    Route::post('/product/{product}/review', [\App\Http\Controllers\Web\ReviewController::class, 'store'])->name('review.store')->middleware('throttle:reviews');

    // Dashboard Redirect — keep only admin dashboard, redirect normal users to orders
    Route::get('/dashboard', function () {
        if (auth()->user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('orders.index');
    })->middleware('verified')->name('dashboard');

    // Customer Orders
    Route::get('/orders', [\App\Http\Controllers\Web\OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [\App\Http\Controllers\Web\OrderController::class, 'show'])->name('orders.show');

    // Customer Return / Replacement Requests
    Route::post('/orders/{order}/return', [\App\Http\Controllers\Web\ReturnRequestController::class, 'store'])->name('orders.return.store');

    // Checkout Routes
    Route::get('/checkout', [\App\Http\Controllers\Web\CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [\App\Http\Controllers\Web\CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/checkout/payment', [\App\Http\Controllers\Web\CheckoutController::class, 'payment'])->name('checkout.payment');
    Route::post('/checkout/callback', [\App\Http\Controllers\Web\CheckoutController::class, 'callback'])->name('checkout.callback');
    Route::get('/checkout/success', [\App\Http\Controllers\Web\CheckoutController::class, 'success'])->name('checkout.success');

    // Wishlist
    Route::get('/wishlist', [\App\Http\Controllers\Web\WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/{product}', [\App\Http\Controllers\Web\WishlistController::class, 'toggle'])->name('wishlist.toggle');
    Route::delete('/wishlist/{product}', [\App\Http\Controllers\Web\WishlistController::class, 'destroy'])->name('wishlist.remove');

    // Profile & Addresses
    Route::resource('addresses', \App\Http\Controllers\Web\AddressController::class)->except(['show']);
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ============================================================================
// Admin Routes (auth + admin role required)
// ============================================================================
Route::prefix('admin')->middleware(['auth', 'admin'])->name('admin.')->group(function () {
    Route::get('/', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

    Route::resource('products', \App\Http\Controllers\Admin\ProductController::class);
    // Extra route to delete individual gallery images
    Route::delete('products/{product}/images/{image}', [\App\Http\Controllers\Admin\ProductController::class, 'destroyImage'])
         ->name('products.images.destroy');

    Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class)->except(['show']);
    Route::resource('brands', \App\Http\Controllers\Admin\BrandController::class)->except(['show']);
    Route::resource('orders', \App\Http\Controllers\Admin\OrderController::class)->except(['create', 'store', 'edit']);
    Route::get('orders/{order}/invoice', [\App\Http\Controllers\Admin\OrderController::class, 'printInvoice'])->name('orders.invoice');
    Route::get('orders/{order}/packing-slip', [\App\Http\Controllers\Admin\OrderController::class, 'printPackingSlip'])->name('orders.packing_slip');
    Route::get('orders/{order}/shipping-label', [\App\Http\Controllers\Admin\OrderController::class, 'printShippingLabel'])->name('orders.shipping_label');
    
    Route::resource('banners', \App\Http\Controllers\Admin\BannerController::class);
    Route::resource('testimonials', \App\Http\Controllers\Admin\TestimonialController::class)->except(['show']);
    Route::resource('pages', \App\Http\Controllers\Admin\PageController::class)->except(['show']);
    Route::resource('media', \App\Http\Controllers\Admin\MediaController::class)->only(['index', 'store', 'destroy']);
    Route::resource('shipping', \App\Http\Controllers\Admin\ShippingZoneController::class)->except(['show']);
    Route::resource('taxes', \App\Http\Controllers\Admin\TaxController::class)->except(['show']);
    Route::resource('payment_methods', \App\Http\Controllers\Admin\PaymentMethodController::class)->only(['index', 'edit', 'update']);
    Route::resource('returns', \App\Http\Controllers\Admin\ReturnRequestController::class)->only(['index', 'show', 'update']);
    Route::resource('customers', \App\Http\Controllers\Admin\CustomerController::class)->only(['index', 'show', 'update']);
    Route::resource('inquiries', \App\Http\Controllers\Admin\ContactInquiryController::class)->only(['index', 'show', 'destroy'])->parameters(['inquiries' => 'inquiry']);
    Route::resource('reviews', \App\Http\Controllers\Admin\ReviewController::class)->except(['create', 'store', 'edit']);
    Route::resource('blogs', \App\Http\Controllers\Admin\BlogController::class);
    Route::resource('coupons', \App\Http\Controllers\Admin\CouponController::class);
    
    Route::get('inventory', [\App\Http\Controllers\Admin\InventoryController::class, 'index'])->name('inventory.index');
    Route::get('inventory/{product}/history', [\App\Http\Controllers\Admin\InventoryController::class, 'history'])->name('inventory.history');
    Route::post('inventory/{product}/adjustment', [\App\Http\Controllers\Admin\InventoryController::class, 'storeAdjustment'])->name('inventory.adjustment');

    Route::get('reports/{tab?}', [\App\Http\Controllers\Admin\ReportController::class, 'index'])->name('reports.index');

    Route::get('settings/{tab?}', [\App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings.index');
    Route::put('settings/{tab}', [\App\Http\Controllers\Admin\SettingController::class, 'update'])->name('settings.update');
});

require __DIR__.'/auth.php';
