<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiProductController;
use App\Http\Controllers\Api\ApiUserController;
use App\Http\Controllers\Api\ApiSettingController;
use App\Http\Controllers\Api\ApiPaymentTransactionController;
use App\Http\Controllers\Api\ApiCartController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

$version = config('magicak.version');

Route::prefix($version)->middleware(['auth:api', 'checkUser'])->name('api.')->group(function () {

    // Product routes
    Route::get('products', [ApiProductController::class, 'getProducts'])->name('products.index');
    Route::get('product/{id}', [ApiProductController::class, 'getProduct'])->name('product.detail');

    // Cart routes
    Route::get('cart', [ApiCartController::class, 'getCart'])->name('cart.index');
    Route::post('add-to-cart', [ApiCartController::class, 'addToCart'])->name('carts.create');

    // User routes
    Route::get('profile', [ApiUserController::class, 'getProfile'])->name('profile.index');
    Route::put('user', [ApiUserController::class, 'updateUser'])->name('user.update');
    Route::put('update-password', [ApiUserController::class, 'updatePassword'])->name('password.update');
    Route::put('upload-avatar', [ApiUserController::class, 'uploadAvatar'])->name('upload-avatar');

    // Settings routes
    Route::get('settings', [ApiSettingController::class, 'getSettings'])->name('settings.index');

    // Payemnt routes
    Route::post('create-stripe-order', [ApiPaymentTransactionController::class, 'createStripeOrder'])->name('stripe-order.create');
    Route::post('create-paypal-order', [ApiPaymentTransactionController::class, 'createPaypalOrder'])->name('paypal-order.create');
    Route::post('capture-paypal-order', [ApiPaymentTransactionController::class, 'capturePaypalOrder'])->name('capture-paypal-order.create');
    Route::post('create-stripe-order-domain', [ApiPaymentTransactionController::class, 'createStripeOrderDomain'])->name('stripe-order-domain.create');
    Route::post('create-paypal-order-domain', [ApiPaymentTransactionController::class, 'createPaypalOrderDomain'])->name('paypal-order-domain.create');
    Route::post('capture-paypal-order-domain', [ApiPaymentTransactionController::class, 'capturePaypalOrderDomain'])->name('capture-paypal-order-domain.create');
    Route::post('create-stripe-order-upgrade-plan', [ApiPaymentTransactionController::class, 'createStripeOrderUpgradePlan'])->name('stripe-order-upgrade-plan.create');
    Route::post('create-paypal-order-upgrade-plan', [ApiPaymentTransactionController::class, 'createPaypalOrderUpgradePlan'])->name('paypal-order-upgrade-plan.create');
    Route::post('capture-paypal-order-upgrade-plan', [ApiPaymentTransactionController::class, 'capturePaypalOrderUpgradePlan'])->name('capture-paypal-order-upgrade-plan.create');

    // Invoice routes
    Route::get('invoices', [ApiPaymentTransactionController::class, 'getInvoices'])->name('invoice.index');
    Route::get('invoice/{id}', [ApiPaymentTransactionController::class, 'getInvoiceById'])->name('invoice.detail');

    // Auth routes
    Route::get('reset-password', [ApiUserController::class, 'resetPassword'])->name('reset-password');
});

Route::prefix($version)->name('api.')->group(function () {

    // auth routes
    Route::post('login', [ApiUserController::class, 'login'])->name('login');
    Route::post('register', [ApiUserController::class, 'register'])->name('register');
});

// API test routes
Route::prefix($version)->name('api.')->group(function () {
    Route::get('/', function () {
        return response()->json(['message' => 'API is working']);
    });
});
