<?php


use App\Http\Controllers\logistic_company\OrderController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\logistic_company\DashboardController;
use App\Http\Controllers\logistic_company\ProfileSetting;
use App\Http\Controllers\logistic_company\DriverController;
use App\Http\Controllers\logistic_company\PaymentController;
Route::get('/dashboard', [DashboardController::class, 'index'])->name('logistic_company_dashboard');
    Route::get('/profile', [ProfileSetting::class, 'profile'])->name('logistic_company_profile');
    Route::post('/profile-update', [ProfileSetting::class, 'profile_update'])->name('logistic_company_profile_update');
    Route::get('/get_profile', [ProfileSetting::class, 'get_profile'])->name('logistic_company_get_profile');
    Route::get('/security', [ProfileSetting::class, 'security'])->name('logistic_company_security');
    Route::post('/security-update', [ProfileSetting::class, 'security_update'])->name('logistic_company_security_update');

    Route::get('/all-drivers', [DriverController::class, 'index'])->name('all_drivers');
    Route::post('/add_drivers', [DriverController::class, 'add_drivers'])->name('add_drivers');
    Route::get('/delete-drivers', [DriverController::class, 'delete_drivers'])->name('delete_drivers');
    Route::get('/status-drivers', [DriverController::class, 'status_drivers'])->name('status_drivers');
    Route::get('/get-drivers', [DriverController::class, 'get_drivers'])->name('get_drivers');
    Route::post('/update-drivers', [DriverController::class, 'update_drivers'])->name('update_drivers');


    Route::get('/all-company-orders', [OrderController::class, 'index'])->name('all_company_orders');
    Route::get('/get-company-order', [OrderController::class, 'get_order'])->name('get_company_order');
    Route::get('/get-company-order-details', [OrderController::class, 'get_company_order_details'])->name('get_company_order_details');
    Route::get('/get-company-areas', [OrderController::class, 'get_areas'])->name('get_company_areas');
    Route::post('/update-company-orders', [OrderController::class, 'update_orders'])->name('update_company_orders');
    Route::post('/assign-company-orders', [OrderController::class, 'assign_orders'])->name('assign_company_orders');
    Route::get('/all-payments', [PaymentController::class, 'index'])->name('all_logistic_company_payments');

