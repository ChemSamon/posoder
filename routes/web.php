<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\POSController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UserController;
use App\Models\Order;
use App\Models\Product;
use App\Models\Category;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;

// Language switcher (not protected)
Route::get('lang/{locale}', function ($locale) {
    session(['locale' => $locale]);
    return redirect()->back();
});

// Login and Register (public)
Route::view('/register', 'Auth.register');
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::get('/', [AuthController::class, 'showLoginForm'])->name('login.form');
Route::view('login', 'Auth.login');
Route::post('/login', [AuthController::class, 'login'])->name('login');

// Protect all other routes with 'auth' middleware
Route::middleware(['auth'])->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Admin Dashboard
    Route::get('/admin', function () {
        $totalProducts = Product::count();
        $totalCategories = Category::count();
        $dailySales = Order::whereDate('created_at', now()->toDateString())->sum('total');
        $monthlySales = Order::whereYear('created_at', now()->year)
                             ->whereMonth('created_at', now()->month)
                             ->sum('total');
        $orders = Order::latest()->take(10)->get();
        $topProducts = OrderItem::select('product_name', DB::raw('SUM(quantity) as total_quantity'))
            ->groupBy('product_name')
            ->orderByDesc('total_quantity')
            ->limit(3)
            ->get();
        return view('home', compact('totalProducts', 'totalCategories', 'dailySales', 'monthlySales', 'orders', 'topProducts'));
    })->name('home');

    // POS & Cart
    Route::get('/pos', [ProductController::class, 'pos'])->name('pos');
    Route::post('/cart/add/{id}', [CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::get('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/cart/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
    Route::get('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
    Route::post('/cart/{id}/note', [CartController::class, 'updateNote'])->name('cart.updateNote');
    // Products
    Route::resource('products', ProductController::class);
    Route::get('/products/search', [ProductController::class, 'search']);

    // Orders
    Route::get('/order/new', [OrderController::class, 'index']);
    Route::get('/orders', [OrderController::class, 'index'])->name('order.index');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders/history', [OrderController::class, 'history'])->name('orders.history');
    Route::get('/orders/history/daily', [OrderController::class, 'daily'])->name('orders.daily');
    Route::get('/orders/history/monthly', [OrderController::class, 'monthly'])->name('orders.monthly');
    Route::post('/orders/history/clear', [OrderController::class, 'clearHistory'])->name('orders.clear');
    Route::get('/monthly-sales-data', [OrderController::class, 'getMonthlySalesData']);
    Route::get('/orders/print/{id}', [OrderController::class, 'print'])->name('orders.print');

    // Categories and Users
    Route::resource('categories', CategoryController::class);
    Route::resource('users', UserController::class);
});
