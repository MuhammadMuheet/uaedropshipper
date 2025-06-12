<?php


use App\Http\Controllers\driver\OrderController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\driver\DashboardController;
use App\Http\Controllers\driver\ProfileSetting;

Route::get('/dashboard', [DashboardController::class, 'index'])->name('driver_dashboard');
Route::get('/profile', [ProfileSetting::class, 'profile'])->name('driver_profile');
Route::post('/profile-update', [ProfileSetting::class, 'profile_update'])->name('driver_profile_update');
Route::get('/get_profile', [ProfileSetting::class, 'get_profile'])->name('driver_get_profile');
Route::get('/security', [ProfileSetting::class, 'security'])->name('driver_security');
Route::post('/security-update', [ProfileSetting::class, 'security_update'])->name('driver_security_update');

Route::get('/all-driver-orders', [OrderController::class, 'index'])->name('all_driver_orders');
Route::get('/delivered-driver-orders', [OrderController::class, 'delivered'])->name('delivered_driver_orders');
Route::get('/shipped-driver-orders', [OrderController::class, 'shipped'])->name('shipped_driver_orders');
Route::get('/canceled-driver-orders', [OrderController::class, 'canceled'])->name('canceled_driver_orders');
Route::get('/get-driver-order', [OrderController::class, 'get_order'])->name('get_driver_order');
Route::get('/get-driver-areas', [OrderController::class, 'get_areas'])->name('get_driver_areas');
Route::post('/mark-delivered', [OrderController::class, 'markDelivered'])->name('order.markDelivered');
Route::get('/status-driver-order', [OrderController::class, 'status_order'])->name('status_driver_order');


