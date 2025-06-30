<?php

use App\Http\Controllers\Auth\UserRegisterController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


//Route::get('/run-my-command', function () {
//    Artisan::call('ChangeStatus:run');
//    return 'Command has been executed';
//});
use Illuminate\Support\Facades\Artisan;

Route::get('/clear-cache', function() {
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('route:clear');
    return 'Cache cleared successfully.';
});
Route::get('/create-storage-link', function () {
    Artisan::call('storage:link');
    return 'The [public/storage] directory has been linked.';
});
Route::get('/orders/export', [OrderController::class, 'export'])->name('orders.export');
Route::post('/orders/export-selected', [OrderController::class, 'exportSelected'])->name('orders.export.selected');
Route::get('/order-details/{id}', [OrderController::class, 'order_details'])->name('order_public_details');
Route::get('/order-rto/{id}', [OrderController::class, 'order_rto_view'])->name('order_public_rto');
Route::post('/order-rto', [OrderController::class, 'order_rto'])->name('order_public_rto_submit');
Route::get('/user-register', [UserRegisterController::class, 'user_register'])->name('user_register');
Route::post('/user-register', [UserRegisterController::class, 'user_register_store'])->name('user_register_store');
Route::get('/', [WelcomeController::class, 'index'])->name('welcome');
Auth::routes();
Route::get('/home', [HomeController::class, 'index'])->name('home');