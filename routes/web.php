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
Route::get('/shop', [\App\Http\Controllers\Web\ShopController::class, 'index'])->name('shop.index');
Route::get('/product/{slug}', [ProductController::class, 'show'])->name('product.show');

// Cart Routes
Route::get('/cart', [\App\Http\Controllers\Web\CartController::class, 'index'])->name('cart.index');
Route::post('/cart', [\App\Http\Controllers\Web\CartController::class, 'store'])->name('cart.add');
Route::put('/cart/{cartItem}', [\App\Http\Controllers\Web\CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/{cartItem}', [\App\Http\Controllers\Web\CartController::class, 'destroy'])->name('cart.remove');

// Checkout Routes
Route::get('/checkout', [\App\Http\Controllers\Web\CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout', [\App\Http\Controllers\Web\CheckoutController::class, 'store'])->name('checkout.store');
Route::get('/checkout/payment', [\App\Http\Controllers\Web\CheckoutController::class, 'payment'])->name('checkout.payment');
Route::get('/checkout/success', [\App\Http\Controllers\Web\CheckoutController::class, 'success'])->name('checkout.success');

// Coupon (AJAX) Routes — accessible to guests too, rate-limited
Route::post('/coupon/apply', [\App\Http\Controllers\Web\CouponController::class, 'apply'])->name('coupon.apply')->middleware('throttle:coupon');
Route::post('/coupon/remove', [\App\Http\Controllers\Web\CouponController::class, 'remove'])->name('coupon.remove');

// Blog Routes
Route::get('/blog', [\App\Http\Controllers\Web\BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [\App\Http\Controllers\Web\BlogController::class, 'show'])->name('blog.show');

// Sitemap
Route::get('/sitemap.xml', [\App\Http\Controllers\Web\SitemapController::class, 'index'])->name('sitemap.index');

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

    // Profile
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
    Route::resource('orders', \App\Http\Controllers\Admin\OrderController::class)->except(['create', 'store', 'edit']);
    Route::resource('customers', \App\Http\Controllers\Admin\CustomerController::class)->only(['index', 'show']);
    Route::resource('blogs', \App\Http\Controllers\Admin\BlogController::class);
    Route::resource('coupons', \App\Http\Controllers\Admin\CouponController::class);
    
    Route::get('settings', [\App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings.index');
    Route::put('settings', [\App\Http\Controllers\Admin\SettingController::class, 'update'])->name('settings.update');
});

require __DIR__.'/auth.php';
