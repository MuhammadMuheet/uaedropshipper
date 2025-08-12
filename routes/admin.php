<?php

use App\Http\Controllers\admin\CategoryController;
use App\Http\Controllers\admin\LocationsController;
use App\Http\Controllers\admin\LogisticCompaniesController;
use App\Http\Controllers\admin\MediaLibraryController;
use App\Http\Controllers\admin\OrderController;
use App\Http\Controllers\admin\ProductController;
use App\Http\Controllers\admin\SellerController;
use App\Http\Controllers\admin\SubCategoriesController;
use App\Http\Controllers\admin\UserLogController;
use App\Http\Controllers\admin\UsersController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\DashboardController;
use App\Http\Controllers\admin\ProfileSetting;
use App\Http\Controllers\admin\RoleController;
use App\Http\Controllers\admin\PaymentController;

Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin_dashboard');
// Types

Route::middleware(['auth', 'module:user_logs,view'])->group(function () {
    //user log
    Route::get('/all-user-logs', [UserLogController::class, 'all_user_logs'])->name('all_user_logs');
});
//settings
Route::middleware(['auth', 'module:settings,smtp'])->group(function () {
    Route::get('/smtp', [ProfileSetting::class, 'smtp'])->name('smtp');
    Route::post('/smtp-update', [ProfileSetting::class, 'smtp_update'])->name('smtp_update');
    Route::get('/get_smtp', [ProfileSetting::class, 'get_smtp'])->name('get_smtp');
});
Route::middleware(['auth', 'module:settings,profile'])->group(function () {
    Route::get('/profile', [ProfileSetting::class, 'profile'])->name('profile');
    Route::post('/profile-update', [ProfileSetting::class, 'profile_update'])->name('profile_update');
    Route::get('/get_profile', [ProfileSetting::class, 'get_profile'])->name('get_profile');
    Route::get('/security', [ProfileSetting::class, 'security'])->name('security');
    Route::post('/security-update', [ProfileSetting::class, 'security_update'])->name('security_update');
});
Route::middleware(['auth', 'module:settings,smtp'])->group(function () {
    Route::get('/smtp', [ProfileSetting::class, 'smtp'])->name('smtp');
    Route::post('/smtp-update', [ProfileSetting::class, 'smtp_update'])->name('smtp_update');
    Route::get('/get_smtp', [ProfileSetting::class, 'get_smtp'])->name('get_smtp');
});
//Types
Route::middleware(['auth', 'module:user_role,view'])->group(function () {
    Route::get('/role', [RoleController::class, 'index'])->name('role');
});
Route::middleware(['auth', 'module:user_role,add'])->group(function () {
    Route::post('/role', [RoleController::class, 'add_role'])->name('add_role');
});
Route::middleware(['auth', 'module:user_role,delete'])->group(function () {
    Route::get('/role-delete', [RoleController::class, 'delete_role'])->name('delete_role');
});
Route::middleware(['auth', 'module:user_role,edit'])->group(function () {
    Route::get('/get-role', [RoleController::class, 'get_role'])->name('get_role');
    Route::post('/update-role', [RoleController::class, 'update_role'])->name('update_role');
});
Route::middleware(['auth', 'module:user_role,permissions'])->group(function () {
    Route::get('/get-permission-update/{role_id}', [RoleController::class, 'permission_update'])->name('permission_update');
    Route::post('/update-permission', [RoleController::class, 'update_permission'])->name('update_permission');
});
Route::middleware(['auth', 'module:users,view'])->group(function () {
    //users
    Route::get('/all-users', [UsersController::class, 'index'])->name('all-users');
});
Route::middleware(['auth', 'module:users,add'])->group(function () {
    Route::post('/add_users', [UsersController::class, 'add_users'])->name('add_users');
});
Route::middleware(['auth', 'module:users,delete'])->group(function () {
    Route::get('/delete-user', [UsersController::class, 'delete_user'])->name('delete_user');
});
Route::middleware(['auth', 'module:users,status'])->group(function () {
    Route::get('/status-user', [UsersController::class, 'status_user'])->name('status_user');
});
Route::middleware(['auth', 'module:users,edit'])->group(function () {
    Route::get('/get-user', [UsersController::class, 'get_user'])->name('get_user');
    Route::post('/update-user', [UsersController::class, 'update_user'])->name('update_user');
});
Route::middleware(['auth', 'module:sellers,view'])->group(function () {
    Route::get('/all-sellers', [SellerController::class, 'index'])->name('all-sellers');
    Route::get('/all-seller-orders/{id}', [SellerController::class, 'all_seller_order'])->name('all_seller_orders_admin');
    Route::get('/all-sub-seller-orders/{id}', [SellerController::class, 'all_sub_seller_order'])->name('all_sub_seller_orders_admin');
    Route::get('/get-seller-order', [SellerController::class, 'get_order'])->name('get_seller_order_admin');
    Route::get('/get-seller-areas', [SellerController::class, 'get_areas'])->name('get_seller_areas_admin');
    Route::get('/all-sub-sellers/{id}', [SellerController::class, 'sub_sellers'])->name('sub_sellers');
    Route::post('/orders/update-status', [OrderController::class, 'updateStatus'])->name('orders.update.status');
});
Route::middleware(['auth', 'module:sellers,add'])->group(function () {
    Route::post('/add_sellers', [SellerController::class, 'add_sellers'])->name('add_sellers');
    Route::post('/add_sub_sellers', [SellerController::class, 'add_sub_sellers'])->name('add_admin_sub_sellers');
});
Route::middleware(['auth', 'module:sellers,delete'])->group(function () {
    Route::get('/delete-sellers', [SellerController::class, 'delete_sellers'])->name('delete_sellers');
});
Route::middleware(['auth', 'module:sellers,status'])->group(function () {
    Route::get('/status-sellers', [SellerController::class, 'status_sellers'])->name('status_sellers');
});
Route::middleware(['auth', 'module:sellers,edit'])->group(function () {
    Route::get('/get-sellers', [SellerController::class, 'get_sellers'])->name('get_sellers');
    Route::get('/get-sellers-service-charges', [SellerController::class, 'get_sellers_service_charges'])->name('get_sellers_service_charges');
    Route::post('/update-sellers-service-charges', [SellerController::class, 'update_sellers_service_charges'])->name('update_sellers_service_charges');
    Route::post('/update-sellers', [SellerController::class, 'update_sellers'])->name('update_sellers');
    Route::post('/update-sub-sellers', [SellerController::class, 'update_sub_sellers'])->name('update_sub_sellers');
});
Route::middleware(['auth', 'module:logistic_companies,view'])->group(function () {
    //users
    Route::get('/all_logistic_companies', [LogisticCompaniesController::class, 'index'])->name('all_logistic_companies');
    Route::get('/all_admin_driver/{id}', [LogisticCompaniesController::class, 'all_driver'])->name('all_admin_driver');
    Route::get('/all-company-orders/{id}', [LogisticCompaniesController::class, 'all_company_order'])->name('all_company_orders_admin');
    Route::get('/get-company-order', [LogisticCompaniesController::class, 'get_order'])->name('get_company_order_admin');
    Route::get('/get-company-areas', [LogisticCompaniesController::class, 'get_areas'])->name('get_company_areas_admin');
    Route::post('/assign-company-orders', [LogisticCompaniesController::class, 'assign_orders'])->name('assign_company_orders_admin');
    Route::get('/all-driver-orders/{id}', [LogisticCompaniesController::class, 'all_driver_orders'])->name('all_driver_admin_orders');
});
Route::middleware(['auth', 'module:logistic_companies,add'])->group(function () {
    Route::post('/add_logistic_companies', [LogisticCompaniesController::class, 'add_logistic_companies'])->name('add_logistic_companies');
    Route::post('/add_admin_driver', [LogisticCompaniesController::class, 'add_driver'])->name('add_admin_driver');
});
Route::middleware(['auth', 'module:logistic_companies,delete'])->group(function () {
    Route::get('/delete-logistic_companies', [LogisticCompaniesController::class, 'delete_logistic_companies'])->name('delete_logistic_companies');
});
Route::middleware(['auth', 'module:logistic_companies,status'])->group(function () {
    Route::get('/status-logistic_companies', [LogisticCompaniesController::class, 'status_logistic_companies'])->name('status_logistic_companies');
});
Route::middleware(['auth', 'module:logistic_companies,edit'])->group(function () {
    Route::get('/get-logistic_companies', [LogisticCompaniesController::class, 'get_logistic_companies'])->name('get_logistic_companies');
    Route::post('/update-logistic_companies', [LogisticCompaniesController::class, 'update_logistic_companies'])->name('update_logistic_companies');
    Route::post('/update-admin_driver', [LogisticCompaniesController::class, 'update_driver'])->name('update_admin_driver');
});
// product category
Route::middleware(['auth', 'module:categories,view'])->group(function () {
    Route::get('/categories', [CategoryController::class, 'all_categories'])->name('all_categories');
});
Route::middleware(['auth', 'module:categories,add'])->group(function () {
    Route::post('/categories', [CategoryController::class, 'add_categories'])->name('add_categories');
});
Route::middleware(['auth', 'module:categories,delete'])->group(function () {
    Route::get('/delete-categories', [CategoryController::class, 'delete_categories'])->name('delete_categories');
});
Route::middleware(['auth', 'module:categories,status'])->group(function () {
    Route::get('/status-categories', [CategoryController::class, 'status_categories'])->name('status_categories');
});
Route::middleware(['auth', 'module:categories,edit'])->group(function () {
    Route::get('/get-categories', [CategoryController::class, 'get_categories'])->name('get_categories');
    Route::post('/update-categories', [CategoryController::class, 'update_categories'])->name('update_categories');
});
// product sub category
Route::middleware(['auth', 'module:sub_categories,view'])->group(function () {
    Route::get('/sub_categories', [SubCategoriesController::class, 'all_sub_categories'])->name('all_sub_categories');
});
Route::middleware(['auth', 'module:sub_categories,add'])->group(function () {
    Route::post('/sub_categories', [SubCategoriesController::class, 'add_sub_categories'])->name('add_sub_categories');
});
Route::middleware(['auth', 'module:sub_categories,delete'])->group(function () {
    Route::get('/delete-sub_categories', [SubCategoriesController::class, 'delete_sub_categories'])->name('delete_sub_categories');
});
Route::middleware(['auth', 'module:sub_categories,status'])->group(function () {
    Route::get('/status-sub_categories', [SubCategoriesController::class, 'status_sub_categories'])->name('status_sub_categories');
});
Route::middleware(['auth', 'module:sub_categories,edit'])->group(function () {
    Route::get('/get-sub_categories', [SubCategoriesController::class, 'get_sub_categories'])->name('get_sub_categories');
    Route::post('/update-sub_categories', [SubCategoriesController::class, 'update_sub_categories'])->name('update_sub_categories');
});
Route::middleware(['auth', 'module:products,add'])->group(function () {
    Route::get('/add-product', [ProductController::class, 'add_product'])->name('add_product');
    Route::post('/add-product', [ProductController::class, 'add_product_store'])->name('add_product_store');
    Route::get('/get_sub_category_for_products', [ProductController::class, 'get_sub_category_for_products'])->name('get_sub_category_for_products');
});
Route::middleware(['auth', 'module:products,view'])->group(function () {
    Route::get('/all-products', [ProductController::class, 'index'])->name('all_products');
    Route::get('/all-product-stocks', [ProductController::class, 'all_product_stocks'])->name('all_product_stocks');
    Route::get('/get-admin-product-variation-stock', [ProductController::class, 'get_admin_product_variation_stock'])->name('get_admin_product_variation_stock');
    Route::get('/get-product', [ProductController::class, 'get_product'])->name('get_product');
});
Route::middleware(['auth', 'module:products,delete'])->group(function () {
    Route::get('/delete-product', [ProductController::class, 'delete_product'])->name('delete_product');
    Route::get('/delete-product-img', [ProductController::class, 'delete_product_img'])->name('delete_product_img');
});
Route::middleware(['auth', 'module:products,status'])->group(function () {
    Route::get('/status-product', [ProductController::class, 'status_product'])->name('status_product');
});
Route::middleware(['auth', 'module:products,edit'])->group(function () {
    Route::get('/update-product/{id}', [ProductController::class, 'update_product'])->name('update_product');
    Route::post('/update-product-store', [ProductController::class, 'update_product_store'])->name('update_product_store');
});
Route::get('/media', [MediaLibraryController::class, 'index'])->name('media.index');
Route::post('/media/upload', [MediaLibraryController::class, 'store'])->name('media.upload');
Route::delete('/media/{id}', [MediaLibraryController::class, 'destroy'])->name('media.delete');
// product State
Route::middleware(['auth', 'module:locations,view'])->group(function () {
    Route::get('/states', [LocationsController::class, 'all_states'])->name('all_states');
    Route::get('/areas', [LocationsController::class, 'all_areas'])->name('all_areas');
    Route::post('/bulk-update-area', [LocationsController::class, 'bulk_update_area'])->name('bulk_update_area');
    Route::post('/import-areas', [LocationsController::class, 'import'])->name('import_areas');
});
Route::middleware(['auth', 'module:locations,add'])->group(function () {
    Route::post('/states', [LocationsController::class, 'add_states'])->name('add_states');
    Route::post('/areas', [LocationsController::class, 'add_areas'])->name('add_areas');
});
Route::middleware(['auth', 'module:locations,delete'])->group(function () {
    Route::get('/delete-states', [LocationsController::class, 'delete_states'])->name('delete_states');
    Route::get('/delete-areas', [LocationsController::class, 'delete_areas'])->name('delete_areas');
});
Route::middleware(['auth', 'module:locations,edit'])->group(function () {
    Route::get('/get-states', [LocationsController::class, 'get_states'])->name('get_states');
    Route::post('/update-states', [LocationsController::class, 'update_states'])->name('update_states');
    Route::get('/get-areas', [LocationsController::class, 'get_areas'])->name('get_admin_areas_edit');
    Route::post('/update-areas', [LocationsController::class, 'update_areas'])->name('update_areas');
});
Route::middleware(['auth', 'module:orders,view'])->group(function () {
    Route::get('/all-orders', [OrderController::class, 'index'])->name('all_admin_orders');
    Route::get('/order-details/{id}', [OrderController::class, 'order_details'])->name('admin_order_details');
    Route::get('/get_order', [OrderController::class, 'get_order'])->name('get_admin_order');
    Route::get('/get-admin-areas', [OrderController::class, 'get_areas'])->name('get_admin_areas');
    Route::get('/get-admin-orders-drivers', [OrderController::class, 'get_drivers'])->name('get_admin_orders_drivers');
    Route::post('/assign-orders', [OrderController::class, 'assign_orders'])->name('assign_orders');
    Route::get('/all-rto-orders', [OrderController::class, 'rto_orders'])->name('all_rto_orders');
    Route::get('/receive-rto', [OrderController::class, 'receive_rto'])->name('receive_rto');
    Route::get('orders/bulk-print', [OrderController::class, 'bulkPrint'])->name('admin.orders.bulk.print');
    Route::delete('/admin/orders/{id}', [OrderController::class, 'destroy'])->name('admin_orders.destroy');
});
Route::middleware(['auth', 'module:payments,view'])->group(function () {
    Route::get('/all-payments', [PaymentController::class, 'index'])->name('all_admin_payments');
    Route::get('/get-admin-transaction-user-type', [PaymentController::class, 'get_transaction_user_type'])->name('get_admin_transaction_user_type');
    Route::post('/give-payment', [PaymentController::class, 'give_payment'])->name('admin_give_payment');
    Route::get('/admin-invoice/{id}', [PaymentController::class, 'invoice'])->name('admin_invoice');

    Route::get('/admin/payment-requests', [PaymentController::class, 'paymentRequestsPage'])->name('payment_requests.page');
    Route::get('/admin/payment-requests/list', [PaymentController::class, 'listPaymentRequests'])->name('payment_requests.list');
    Route::post('/admin/payment-requests/action', [PaymentController::class, 'handlePaymentAction'])->name('payment_request_action');
});
Route::middleware(['auth', 'module:orders,edit'])->group(function () {
    Route::get('/get-areas-shipping', [OrderController::class, 'get_areas_shipping'])->name('admin_get_areas_shipping');
    Route::get('/admin_orders/{id}/edit', [OrderController::class, 'edit'])->name('admin_orders.edit');
    Route::post('/admin_orders/{id}/update', [OrderController::class, 'update'])->name('admin_orders.update');
    Route::post('/orders/{id}/add-item', [OrderController::class, 'addItemToOrder'])->name('admin_orders.add_item');
    Route::post('/orders/{id}/update-item', [OrderController::class, 'updateOrderItem'])->name('admin_orders.update_item');
    Route::post('/orders/{id}/remove-item', [OrderController::class, 'removeOrderItem'])->name('admin_orders.remove_item');
    Route::get('/get-product-variations', [OrderController::class, 'getProductVariations'])->name('admin_get_product_variations');
    Route::get('/get-seller-service-charges-admin', [OrderController::class, 'get_seller_service_charges'])->name('get_seller_service_charges_admin');
});

Route::get('/admin/payments/{id}/invoice/download', [PaymentController::class, 'downloadInvoice'])->name('admin.payments.invoice.download');

Route::middleware(['auth', 'module:orders,status'])->group(function () {
    Route::get('/status-order', [OrderController::class, 'status_order'])->name('status_order');
});
