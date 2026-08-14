<?php

use App\Http\Controllers\Api\V1\AddressController;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\BlogController;
use App\Http\Controllers\Api\V1\BrandController;
use App\Http\Controllers\Api\V1\CartController;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\CheckoutController;
use App\Http\Controllers\Api\V1\CouponController;
use App\Http\Controllers\Api\V1\HomeController;
use App\Http\Controllers\Api\V1\OrderController;
use App\Http\Controllers\Api\V1\PageController;
use App\Http\Controllers\Api\V1\ProductController;
use App\Http\Controllers\Api\V1\ProfileController;
use App\Http\Controllers\Api\V1\ReturnRequestController;
use App\Http\Controllers\Api\V1\ReviewController;
use App\Http\Controllers\Api\V1\SearchController;
use App\Http\Controllers\Api\V1\SettingsController;
use App\Http\Controllers\Api\V1\WishlistController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Kare Ons Customer API (v1)
|--------------------------------------------------------------------------
|
| A standalone, versioned REST API for customer-facing features (auth,
| catalog, cart, checkout, orders, etc.) for any future headless client.
| Auth is Sanctum Bearer tokens (no cookies/sessions) — see docs/API.md for
| the full reference. This app is not currently wired to any separate
| frontend — the Blade storefront in routes/web.php remains the live,
| fully-functional customer + admin experience. The admin panel is Blade
| only and intentionally out of scope for this API.
|
*/

Route::prefix('v1')->name('api.v1.')->group(function () {

    // ---------------------------------------------------------------------
    // Public — content & catalog
    // ---------------------------------------------------------------------
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings');

    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('/categories/{slug}', [CategoryController::class, 'show'])->name('categories.show');

    Route::get('/brands', [BrandController::class, 'index'])->name('brands.index');

    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/{slug}', [ProductController::class, 'show'])->name('products.show');

    Route::get('/search/suggest', [SearchController::class, 'suggest'])
        ->middleware('throttle:search')
        ->name('search.suggest');

    Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
    Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');

    Route::get('/pages/{slug}', [PageController::class, 'show'])->name('pages.show');
    Route::post('/contact', [PageController::class, 'submitContact'])->name('contact.store');

    Route::post('/coupons/validate', [CouponController::class, 'validateCoupon'])
        ->middleware('throttle:coupon')
        ->name('coupons.validate');

    // ---------------------------------------------------------------------
    // Auth
    // ---------------------------------------------------------------------
    Route::prefix('auth')->name('auth.')->group(function () {
        Route::post('/register', [AuthController::class, 'register'])->name('register');
        Route::post('/login', [AuthController::class, 'login'])->name('login');
        Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('forgot-password');
        Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('reset-password');

        // Opened directly from the verification email — signed URL is the
        // auth, no Sanctum token required (the browser has no session/token
        // at that point).
        Route::get('/verify-email/{id}/{hash}', [AuthController::class, 'verifyEmail'])
            ->middleware(['signed', 'throttle:6,1'])
            ->name('verify-email');

        Route::middleware('auth:sanctum')->group(function () {
            Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
            Route::get('/user', [AuthController::class, 'user'])->name('user');
            Route::post('/email/resend', [AuthController::class, 'resendVerification'])
                ->middleware('throttle:6,1')
                ->name('email.resend');
        });
    });

    // ---------------------------------------------------------------------
    // Authenticated customer area
    // ---------------------------------------------------------------------
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

        Route::apiResource('addresses', AddressController::class)->except(['show']);

        Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
        Route::post('/cart', [CartController::class, 'store'])->name('cart.store');
        Route::put('/cart/{cartItem}', [CartController::class, 'update'])->name('cart.update');
        Route::delete('/cart/{cartItem}', [CartController::class, 'destroy'])->name('cart.destroy');

        Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
        Route::post('/wishlist/{product}', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
        Route::delete('/wishlist/{product}', [WishlistController::class, 'destroy'])->name('wishlist.destroy');

        Route::post('/products/{product}/reviews', [ReviewController::class, 'store'])
            ->middleware('throttle:reviews')
            ->name('products.reviews.store');

        Route::get('/checkout/summary', [CheckoutController::class, 'summary'])->name('checkout.summary');
        Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
        Route::post('/checkout/verify-payment', [CheckoutController::class, 'verifyPayment'])->name('checkout.verify-payment');

        Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
        Route::post('/orders/{order}/return', [ReturnRequestController::class, 'store'])->name('orders.return');
    });
});
