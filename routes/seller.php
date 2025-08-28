<?php

use App\Http\Controllers\seller\CartController;
use App\Http\Controllers\seller\OrderController;
use App\Http\Controllers\seller\ProductController;
use App\Http\Controllers\seller\ProfileController;
use App\Http\Controllers\seller\DashboardController;
use App\Http\Controllers\seller\SellerRoleController;
use App\Http\Controllers\seller\SubSellerController;
use App\Http\Controllers\seller\PaymentController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;



Route::get('/dashboard', [DashboardController::class, 'index'])->name('seller_dashboard');
Route::middleware(['auth', 'seller_module:products,view'])->group(function () {
    Route::get('/products', [ProductController::class, 'index'])->name('products');
    Route::get('/product/{id}', [ProductController::class, 'show'])->name('product.show'); // New route for product detail
    Route::get('/get-seller-product-data', [ProductController::class, 'get_seller_product_data'])->name('get_seller_product_data');
    Route::get('/get-seller-product-variation-price', [ProductController::class, 'get_seller_product_variation_price'])->name('get_seller_product_variation_price');
});
Route::middleware(['auth', 'seller_module:cart,view'])->group(function () {
    Route::get('/all-cart', [CartController::class, 'index'])->name('all_cart');
});
Route::middleware(['auth', 'seller_module:cart,add'])->group(function () {
    Route::post('/add-to-cart', [CartController::class, 'add_to_cart'])->name('add_to_cart');
});
Route::middleware(['auth', 'seller_module:cart,edit'])->group(function () {
    Route::get('/get-cart', [CartController::class, 'get_cart'])->name('get_cart');
    Route::post('/update-cart', [CartController::class, 'update_cart'])->name('update_cart');
});
Route::middleware(['auth', 'seller_module:cart,delete'])->group(function () {
    Route::get('/delete-cart', [CartController::class, 'delete_cart'])->name('delete_cart');
});
Route::middleware(['auth', 'seller_module:orders,checkout'])->group(function () {
    Route::get('/checkout', [OrderController::class, 'checkout'])->name('checkout');
    Route::get('/get-cart-data', [OrderController::class, 'getCartData'])->name('get_cart_data');
    Route::post('/update-cart-ajax', [OrderController::class, 'updateCartAjax'])->name('update_cart_ajax');
    Route::get('/get-areas', [OrderController::class, 'get_areas'])->name('get_areas');
    Route::get('/get-areas-shipping', [OrderController::class, 'get_areas_shipping'])->name('get_areas_shipping');
    Route::get('/get-cart-subtotal', [OrderController::class, 'get_cart_subtotal'])->name('get_cart_subtotal');
    Route::get('/get-seller-service-charges', [OrderController::class, 'get_seller_service_charges'])->name('get_seller_service_charges');
});
Route::middleware(['auth', 'seller_module:orders,view'])->group(function () {
    Route::get('/all-orders', [OrderController::class, 'index'])->name('all_orders');
    Route::get('/get_order', [OrderController::class, 'get_order'])->name('get_order');
});
Route::middleware(['auth', 'seller_module:orders,add'])->group(function () {
    Route::post('/place-order', [OrderController::class, 'placeOrder'])->name('place_order');
});
Route::middleware(['auth', 'seller_module:orders,edit'])->group(function () {
    Route::get('/orders/{id}/edit', [OrderController::class, 'edit'])->name('orders.edit');
    Route::post('/orders/{id}/update', [OrderController::class, 'update'])->name('orders.update');
    Route::post('/orders/{id}/add-item', [OrderController::class, 'addItemToOrder'])->name('orders.add_item');
    Route::post('/orders/{id}/update-item', [OrderController::class, 'updateOrderItem'])->name('orders.update_item');
    Route::post('/orders/{id}/remove-item', [OrderController::class, 'removeOrderItem'])->name('orders.remove_item');
    Route::get('/get-product-variations', [OrderController::class, 'getProductVariations'])->name('get_product_variations');
});

Route::middleware(['auth', 'seller_module:settings,profile'])->group(function () {
    Route::get('/seller-profile', [ProfileController::class, 'profile'])->name('seller_profile');
    Route::post('/seller-profile-update', [ProfileController::class, 'profile_update'])->name('seller_profile_update');
    Route::get('/seller-security', [ProfileController::class, 'security'])->name('seller_security');
    Route::get('/seller_get_profile', [ProfileController::class, 'get_profile'])->name('seller_get_profile');
    Route::post('/seller-security-update', [ProfileController::class, 'security_update'])->name('seller_security_update');
});
//Types
Route::middleware(['auth', 'seller_module:seller_role,view'])->group(function () {
    Route::get('/seller-role', [SellerRoleController::class, 'index'])->name('seller_role');
});
Route::middleware(['auth', 'seller_module:seller_role,add'])->group(function () {
    Route::post('/seller-role', [SellerRoleController::class, 'add_role'])->name('seller_add_role');
});
Route::middleware(['auth', 'seller_module:seller_role,delete'])->group(function () {
    Route::get('/seller-role-delete', [SellerRoleController::class, 'delete_role'])->name('seller_delete_role');
});
Route::middleware(['auth', 'seller_module:seller_role,edit'])->group(function () {
    Route::get('/seller-get-role', [SellerRoleController::class, 'get_role'])->name('seller_get_role');
    Route::post('/seller-update-role', [SellerRoleController::class, 'update_role'])->name('seller_update_role');
});
Route::middleware(['auth', 'seller_module:seller_role,permissions'])->group(function () {
    Route::get('/seller-get-permission-update/{role_id}', [SellerRoleController::class, 'permission_update'])->name('seller_permission_update');
    Route::post('/seller-update-permission', [SellerRoleController::class, 'update_permission'])->name('seller_update_permission');
});
Route::middleware(['auth', 'seller_module:sub_sellers,view'])->group(function () {
    Route::get('/all-sub-sellers', [SubSellerController::class, 'index'])->name('all_sub_sellers');
});
Route::middleware(['auth', 'seller_module:sub_sellers,add'])->group(function () {
    Route::post('/add_sub_sellers', [SubSellerController::class, 'add_sub_sellers'])->name('add_sub_sellers');
});
Route::middleware(['auth', 'seller_module:sub_sellers,delete'])->group(function () {
    Route::get('/delete-sub-seller', [SubSellerController::class, 'delete_sub_seller'])->name('delete_sub_seller');
});
Route::middleware(['auth', 'seller_module:sub_sellers,status'])->group(function () {
    Route::get('/status-sub_seller', [SubSellerController::class, 'status_sub_seller'])->name('status_sub_seller');
});
Route::middleware(['auth', 'seller_module:sub_sellers,edit'])->group(function () {
    Route::get('/get-sub_seller', [SubSellerController::class, 'get_sub_seller'])->name('get_sub_seller');
    Route::post('/update-sub_seller', [SubSellerController::class, 'update_sub_seller'])->name('update_sub_seller');
});
Route::middleware(['auth', 'seller_module:payments,view'])->group(function () {
    Route::get('/all-payments', [PaymentController::class, 'index'])->name('all_seller_payments');
    Route::post('/seller/payment-request', [PaymentController::class, 'sendPaymentRequest'])->name('send_payment_request');
});
// Route::get('/detailss', [PaymentController::class, 'index_Product']);