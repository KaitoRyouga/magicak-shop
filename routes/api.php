<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiDataCenterLocationController;
use App\Http\Controllers\Api\ApiUserWebsiteController;
use App\Http\Controllers\Api\ApiUserController;
use App\Http\Controllers\Api\ApiTemplateController;
use App\Http\Controllers\Api\ApiHostingController;
use App\Http\Controllers\Api\ApiSettingController;
use App\Http\Controllers\Api\ApiDomainController;
use App\Http\Controllers\Api\ApiPaymentTransactionController;

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

    // Locations routes
    Route::get('data-center-locations/{id}', [ApiDataCenterLocationController::class, 'getLocationWithHostingPlanType'])->name('data-center-location.index');

    // User website routes
    Route::get('user-website', [ApiUserWebsiteController::class, 'getUserWebsites'])->name('user-website.index');
    Route::get('user-website/{id}', [ApiUserWebsiteController::class, 'getUserWebsite'])->name('user-website.detail');
    Route::put('user-website/{id}', [ApiUserWebsiteController::class, 'initUserWebsite'])->name('user-website.init');
    Route::delete('user-website-pending/{id}', [ApiUserWebsiteController::class, 'deleteByUserIdAndUserWebsiteId'])->name('user-website-pending.delete');
    Route::delete('user-website/{id}', [ApiUserWebsiteController::class, 'deleteUserWebsiteById'])->name('user-website.delete');
    Route::post('user-website', [ApiUserWebsiteController::class, 'createUserWebsite'])->name('user-website.create');
    Route::post('check-business-name', [ApiUserWebsiteController::class, 'checkBusinessNameExist'])->name('check-business-name');
    Route::post('check-domain-name', [ApiDomainController::class, 'checkDomainNameExist'])->name('check-domain-name');
    Route::post('trigger-init-user-website', [ApiUserWebsiteController::class, 'triggerInitUserWebsite'])->name('website.init');
    Route::put('update-domain-user-website', [ApiUserWebsiteController::class, 'updateDomainUserWebsite'])->name('website.check-push-noti-ios');
    Route::put('upgrade-plan', [ApiUserWebsiteController::class, 'upgradePlan'])->name('user-website.upgrade-plan');
    Route::put('update-website-message', [ApiUserWebsiteController::class, 'updateWebsiteMessage'])->name('website-message.update-website-message');
    Route::post('choose-domain', [ApiUserWebsiteController::class, 'chooseDomain'])->name('choose-domain');
    Route::get('user-website-updating-domain', [ApiUserWebsiteController::class, 'getUserWebsiteUpdatingDomain'])->name('user-website-updating-domain');

    // User routes
    Route::get('profile', [ApiUserController::class, 'getProfile'])->name('profile.index');
    Route::put('user', [ApiUserController::class, 'updateUser'])->name('user.update');
    Route::put('update-password', [ApiUserController::class, 'updatePassword'])->name('password.update');
    Route::put('upload-avatar', [ApiUserController::class, 'uploadAvatar'])->name('upload-avatar');

    // Template routes
    Route::get('template-categories', [ApiTemplateController::class, 'getTemplateCategories'])->name('template-categories.index');
    Route::get('template-categories/{id}', [ApiTemplateController::class, 'getTemplateCategoryById'])->name('template-categories-by-type');
    Route::get('templates/{id}', [ApiTemplateController::class, 'getTemplateWithCategoryAndSub'])->name('templates.index');
    Route::get('template-types', [ApiTemplateController::class, 'getTemplateTypes'])->name('template-type.index');

    // Hosting routes
    Route::post('hosting-plans', [ApiHostingController::class, 'getHostingPlansWithLocationAndType'])->name('hosting-plans.index');
    Route::get('hosting-types', [ApiHostingController::class, 'getHostingPlanTypes'])->name('hosting-types.index');

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

    // Domain routes
    Route::get('domain', [ApiDomainController::class, 'getDomains'])->name('domains.index');
    Route::post('domain', [ApiDomainController::class, 'createDomain'])->name('domains.create');
    Route::post('trigger-init-domain', [ApiDomainController::class, 'triggerInitDomain'])->name('domains.init');
    Route::get('list-domain-update', [ApiDomainController::class, 'getListDomainUpdate'])->name('domains.list-domain-update');
    Route::delete('domain/{id}', [ApiDomainController::class, 'deleteDomain'])->name('domains.delete');

    // Invoice routes
    Route::get('invoices', [ApiPaymentTransactionController::class, 'getInvoices'])->name('invoice.index');
    Route::get('invoice/{id}', [ApiPaymentTransactionController::class, 'getInvoiceById'])->name('invoice.detail');

    // Auth routes
    Route::get('reset-password', [ApiUserController::class, 'resetPassword'])->name('reset-password');

    Route::get('test-bug', [ApiUserController::class, 'testBug'])->name('test-bug');
});

Route::prefix($version)->name('api.')->group(function () {

    // auth routes
    Route::post('login', [ApiUserController::class, 'login'])->name('login');
    Route::post('register', [ApiUserController::class, 'register'])->name('register');

    Route::post('add-to-group', [ApiUserController::class, 'addToGroup'])->name('add-to-group');

});

// API test routes
Route::prefix($version)->name('api.')->group(function () {
    Route::get('/', function () {
        return response()->json(['message' => 'API is working']);
    });
    Route::get('test-rabbitmq', [ApiUserController::class, 'testRabbitMQ'])->name('website.test');
    Route::get('check-push-noti', [ApiUserController::class, 'checkPushNotification'])->name('website.check-push-noti');
});
