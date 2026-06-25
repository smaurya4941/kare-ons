<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\ProductController;

Route::get('/', [HomeController::class, 'index'])->name('home');
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

// Blog Routes
Route::get('/blog', [\App\Http\Controllers\Web\BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [\App\Http\Controllers\Web\BlogController::class, 'show'])->name('blog.show');

// Sitemap
Route::get('/sitemap.xml', [\App\Http\Controllers\Web\SitemapController::class, 'index'])->name('sitemap.index');

Route::middleware('auth')->group(function () {
    Route::post('/product/{product}/review', [\App\Http\Controllers\Web\ReviewController::class, 'store'])->name('review.store');
});

// Customer Dashboard / Breeze default
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Profile Routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin Routes (Protected)
Route::prefix('admin')->middleware(['web', 'admin'])->name('admin.')->group(function () {
    Route::get('/', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::resource('products', \App\Http\Controllers\Admin\ProductController::class);
    Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class);
    Route::resource('orders', \App\Http\Controllers\Admin\OrderController::class);
    Route::resource('customers', \App\Http\Controllers\Admin\CustomerController::class);
});

require __DIR__.'/auth.php';
