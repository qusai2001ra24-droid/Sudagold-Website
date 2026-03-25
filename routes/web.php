<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\GoldPriceController;
use App\Http\Controllers\Admin\ReportsController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\GoldAnalyticsController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::get('/catalog', [HomeController::class, 'catalog'])->name('catalog');
Route::get('/product/{id}', [HomeController::class, 'product'])->name('product');
Route::get('/gold-analytics', [GoldAnalyticsController::class, 'index'])->name('gold-analytics');
Route::get('/api/gold-prices', [GoldAnalyticsController::class, 'apiPrices'])->name('api.gold-prices');

Route::get('/cart', [CartController::class, 'index'])->name('cart');
Route::get('/cart/data', [CartController::class, 'getCart'])->name('cart.data');

Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update-quantity', [CartController::class, 'updateQuantity'])->name('cart.update-quantity');
Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('dashboard');

    // Checkout Routes
    Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');
    Route::get('/order/confirmation/{id}', [CheckoutController::class, 'confirmation'])->name('order.confirmation');

    // Admin Routes
    Route::prefix('admin')->name('admin.')->group(function () {
        // Dashboard
        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('dashboard');

        // Products
        Route::middleware('permission:view_products')->group(function () {
            Route::get('/products', [ProductController::class, 'index'])->name('products.index');
            Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
            Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
            Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
        });

        Route::middleware('permission:create_products')->group(function () {
            Route::post('/products', [ProductController::class, 'store'])->name('products.store');
        });

        Route::middleware('permission:edit_products')->group(function () {
            Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
            Route::patch('/products/{product}/stock', [ProductController::class, 'updateStock'])->name('products.updateStock');
        });

        Route::middleware('permission:delete_products')->group(function () {
            Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
        });

        // Categories
        Route::middleware('permission:view_categories')->group(function () {
            Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
            Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
            Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
        });

        Route::middleware('permission:create_categories')->group(function () {
            Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
        });

        Route::middleware('permission:edit_categories')->group(function () {
            Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
            Route::get('/categories/{category}/toggle', [CategoryController::class, 'toggleStatus'])->name('categories.toggle');
        });

        Route::middleware('permission:delete_categories')->group(function () {
            Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');
        });

        // Orders
        Route::middleware('permission:view_orders')->group(function () {
            Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
            Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
        });

        Route::middleware('permission:edit_orders')->group(function () {
            Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
        });

        Route::middleware('permission:delete_orders')->group(function () {
            Route::delete('/orders/{order}', [OrderController::class, 'destroy'])->name('orders.destroy');
        });

        // Users (Admin only)
        Route::middleware('role:admin')->group(function () {
            Route::get('/users', [UserController::class, 'index'])->name('users.index');
            Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
            Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
            Route::post('/users', [UserController::class, 'store'])->name('users.store');
            Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
            Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
            Route::get('/users/{user}/toggle', [UserController::class, 'toggleStatus'])->name('users.toggleStatus');

            // Gold Prices
            Route::get('/gold-prices', [GoldPriceController::class, 'index'])->name('gold-prices.index');
            Route::get('/gold-prices/create', [GoldPriceController::class, 'create'])->name('gold-prices.create');
            Route::get('/gold-prices/{goldPrice}/edit', [GoldPriceController::class, 'edit'])->name('gold-prices.edit');
            Route::post('/gold-prices', [GoldPriceController::class, 'store'])->name('gold-prices.store');
            Route::put('/gold-prices/{goldPrice}', [GoldPriceController::class, 'update'])->name('gold-prices.update');
            Route::delete('/gold-prices/{goldPrice}', [GoldPriceController::class, 'destroy'])->name('gold-prices.destroy');
            Route::get('/gold-prices/{goldPrice}/set-current', [GoldPriceController::class, 'setCurrent'])->name('gold-prices.setCurrent');

            // Reports
            Route::get('/reports', [ReportsController::class, 'index'])->name('reports.index');
            Route::get('/reports/export', [ReportsController::class, 'export'])->name('reports.export');

            // Analytics
            Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics.index');
            Route::get('/analytics/daily-sales', [AnalyticsController::class, 'dailySales'])->name('analytics.daily-sales');
            Route::get('/analytics/monthly-revenue', [AnalyticsController::class, 'monthlyRevenue'])->name('analytics.monthly-revenue');
            Route::get('/analytics/yearly-revenue', [AnalyticsController::class, 'yearlyRevenue'])->name('analytics.yearly-revenue');
            Route::get('/analytics/top-products', [AnalyticsController::class, 'topProducts'])->name('analytics.top-products');
            Route::get('/analytics/inventory', [AnalyticsController::class, 'inventoryReport'])->name('analytics.inventory');
            Route::get('/analytics/customers', [AnalyticsController::class, 'customerAnalytics'])->name('analytics.customers');
            Route::get('/analytics/export-pdf', [AnalyticsController::class, 'exportPdf'])->name('analytics.export-pdf');
            Route::get('/analytics/export-excel', [AnalyticsController::class, 'exportExcel'])->name('analytics.export-excel');
        });
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
