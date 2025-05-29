<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

// Controllers
use App\Http\Controllers\{
    ProductController,
    OrderController,
    CartController,
    CheckoutController,
    ProfileController,
    NotificationController,
    DashboardController,
    PhoneController,
    AddressController,
    ContactController,
    PolicyController,
    HomeController,
};

// Admin Controllers
use App\Http\Controllers\Admin\{
    OrderController as AdminOrderController,
    ProductController as AdminProductController,
    CategoryController as AdminCategoryController,
    ReportController as AdminReportController,
    DashboardController as AdminDashboardController,
    CouponController,
    QuantityDiscountController
};

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');

// Static Pages Routes
Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/services', function () {
    return view('services');
})->name('services');

Route::get('/contact', function () {
    return view('contact');
})->name('contact');

Route::get('/shop', function () {
    return view('shop');
})->name('shop');

// Products Routes (Public)
Route::prefix('products')->name('products.')->group(function () {
    Route::get('/', [ProductController::class, 'index'])->name('index');
    Route::post('/filter', [ProductController::class, 'filter'])->name('filter');
    Route::get('/{product}/details', [ProductController::class, 'getProductDetails'])->name('details');
    Route::get('/{product}', [ProductController::class, 'show'])->name('show');
});

// Auth Routes
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    // Common Routes (for all authenticated users)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Notifications
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::post('/{notification}/mark-as-read', [NotificationController::class, 'markAsRead'])->name('mark-as-read');
        Route::post('/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('mark-all-read');
    });

    // Customer Routes
    Route::middleware(['role:customer'])->group(function () {


        // Phones
        Route::post('/phones', [PhoneController::class, 'store']);
        Route::get('/phones/{phone}', [PhoneController::class, 'show']);
        Route::put('/phones/{phone}', [PhoneController::class, 'update']);
        Route::delete('/phones/{phone}', [PhoneController::class, 'destroy']);
        Route::post('/phones/{phone}/make-primary', [PhoneController::class, 'makePrimary']);

        // Addresses
        Route::post('/addresses', [AddressController::class, 'store']);
        Route::get('/addresses/{address}', [AddressController::class, 'show']);
        Route::put('/addresses/{address}', [AddressController::class, 'update']);
        Route::delete('/addresses/{address}', [AddressController::class, 'destroy']);
        Route::post('/addresses/{address}/make-primary', [AddressController::class, 'makePrimary']);

        // Cart
        Route::prefix('cart')->name('cart.')->group(function () {
            Route::get('/', [CartController::class, 'index'])->name('index');
            Route::post('/add', [ProductController::class, 'addToCart'])->name('add');
            Route::get('/items', [ProductController::class, 'getCartItems'])->name('items');
            Route::patch('/update/{cartItem}', [CartController::class, 'updateQuantity'])->name('update');
            Route::delete('/remove/{cartItem}', [CartController::class, 'removeItem'])->name('remove');
            Route::post('/clear', [CartController::class, 'clear'])->name('clear');
        });

        // Checkout
        Route::controller(CheckoutController::class)->prefix('checkout')->name('checkout.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/', 'store')->name('store')->middleware('web');
            Route::post('/apply-coupon', 'applyCoupon')->name('apply-coupon');
        });

        // Orders
        Route::prefix('orders')->name('orders.')->group(function () {
            Route::get('/', [OrderController::class, 'index'])->name('index');
            Route::get('/{order:uuid}', [OrderController::class, 'show'])->name('show');
        });
    });

    // Admin Routes
    Route::middleware(['role:admin', \App\Http\Middleware\AdminPopupAuthMiddleware::class])
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {
            // Dashboard
            Route::get('/dashboard/', [AdminDashboardController::class, 'index'])->name('dashboard');

            // Products Management
            Route::middleware(['permission:manage products'])->group(function () {
                Route::resource('products', AdminProductController::class);
                Route::resource('categories', AdminCategoryController::class);
            });

            // Coupons & Discounts Management
            Route::middleware(['permission:manage products'])->group(function () {
                Route::resource('coupons', CouponController::class);
                Route::post('coupons/generate-code', [CouponController::class, 'generateCode'])->name('coupons.generate-code');
            });

            // Orders Management
            Route::middleware(['permission:manage orders'])->group(function () {
                Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
                Route::get('/orders/{order:uuid}', [AdminOrderController::class, 'show'])->name('orders.show');
                Route::put('/orders/{order:uuid}/status', [AdminOrderController::class, 'updateStatus'])
                    ->name('orders.update-status');
                Route::put('/orders/{order:uuid}/payment-status', [AdminOrderController::class, 'updatePaymentStatus'])
                    ->name('orders.update-payment-status');
                Route::patch('/orders/{order:uuid}/payment', [AdminOrderController::class, 'updatePayment'])
                    ->name('orders.update-payment');
            });

            // Reports Management
            Route::middleware(['permission:manage reports'])->group(function () {
                Route::get('/reports', [AdminReportController::class, 'index'])->name('reports.index');
            });

            // Quantity Discounts Routes
            Route::resource('quantity-discounts', QuantityDiscountController::class);
        });
});


// Protected Cart Routes
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/cart/add', [ProductController::class, 'addToCart'])->name('cart.add');
    Route::get('/cart/items', [ProductController::class, 'getCartItems'])->name('cart.items');
    Route::patch('/cart/items/{cartItem}', [ProductController::class, 'updateCartItem'])->name('cart.update-item');
    Route::delete('/cart/remove/{cartItem}', [ProductController::class, 'removeCartItem'])->name('cart.remove-item');
});

// مسارات لوحة تحكم العميل
Route::middleware('client')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
    // ... باقي مسارات العميل
});



Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

// مسارات السلة
Route::middleware(['auth'])->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::patch('/cart/items/{cartItem}', [CartController::class, 'updateItem'])->name('cart.items.update');
    Route::delete('/cart/items/{cartItem}', [CartController::class, 'removeItem'])->name('cart.items.remove');
    Route::get('/cart/count', [CartController::class, 'getCartCount'])->name('cart.count');
});

Route::get('/policy', [PolicyController::class, 'index'])->name('policy');
Route::post('/admin/update-fcm-token', [App\Http\Controllers\Admin\DashboardController::class, 'updateFcmToken'])
    ->middleware(['auth', 'admin']);
