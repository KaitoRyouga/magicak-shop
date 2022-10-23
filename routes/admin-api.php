<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiDataCenterLocationController;
use App\Http\Controllers\Api\ApiHostingController;
use App\Http\Controllers\Api\ApiTemplateController;
use App\Http\Controllers\Api\ApiClusterController;
use App\Http\Controllers\Api\ApiSystemDomainController;
use App\Http\Controllers\Api\ApiSettingController;
use App\Http\Controllers\AdminAPI\ApiTemporaryDomainController;
use App\Http\Controllers\AdminAPI\ApiDomainController;

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

Route::prefix($version)->middleware(['auth:api', 'checkUser'])->name('admin-api.')->group(function () {

    // Locations routes
    Route::get('data-center-locations', [ApiDataCenterLocationController::class, 'listDataCenterLocation'])->name('data-center-location.index');
    Route::post('data-center-locations', [ApiDataCenterLocationController::class, 'createDataCenterLocation'])->name('data-center-location.store');
    Route::put('data-center-locations/{id}', [ApiDataCenterLocationController::class, 'updateDataCenterLocation'])->name('data-center-location.update');
    Route::delete('data-center-locations/{id}', [ApiDataCenterLocationController::class, 'deleteDataCenterLocation'])->name('data-center-location.destroy');
    Route::get('data-center-location-dropdowns', [ApiDataCenterLocationController::class, 'listDataCenterLocationDropdowns'])->name('data-center-location.dropdown');

    // Hosting plans routes
    Route::get('hosting-plans', [ApiHostingController::class, 'listHostingPlans'])->name('hosting-plans.index');
    Route::post('hosting-plans', [ApiHostingController::class, 'createHostingPlans'])->name('hosting-plans.store');
    Route::put('hosting-plans/{id}', [ApiHostingController::class, 'updateHostingPlans'])->name('hosting-plans.update');
    Route::delete('hosting-plans/{id}', [ApiHostingController::class, 'deleteHostingPlans'])->name('hosting-plans.destroy');
    Route::get('hosting-plan-type-dropdowns', [ApiHostingController::class, 'listHostingPlanTypeDropdowns'])->name('hosting-plan-types.dropdown');
    Route::get('hosting-plan-platform-dropdowns', [ApiHostingController::class, 'listHostingPlanPlatformDropdowns'])->name('hosting-plan-platforms.dropdown');

    // Template categories routes
    Route::get('template-categories', [ApiTemplateController::class, 'listTemplateCategories'])->name('template-categories.index');
    Route::post('template-categories', [ApiTemplateController::class, 'createTemplateCategories'])->name('template-categories.store');
    Route::put('template-categories/{id}', [ApiTemplateController::class, 'updateTemplateCategories'])->name('template-categories.update');
    Route::delete('template-categories/{id}', [ApiTemplateController::class, 'deleteTemplateCategories'])->name('template-categories.destroy');
    Route::get('template-categories-dropdowns', [ApiTemplateController::class, 'listTemplateCategoriesDropdowns'])->name('template-categories.dropdown');

    // Template routes
    Route::get('template', [ApiTemplateController::class, 'listTemplate'])->name('template.index');
    Route::post('template', [ApiTemplateController::class, 'createTemplate'])->name('template.store');
    Route::put('template/{id}', [ApiTemplateController::class, 'updateTemplate'])->name('template.update');
    Route::delete('template/{id}', [ApiTemplateController::class, 'deleteTemplate'])->name('template.destroy');

    // Cluster domain routes
    Route::get('hosting-clusters', [ApiClusterController::class, 'getHostingClusters'])->name('hosting-clusters.index');
    Route::post('hosting-clusters', [ApiClusterController::class, 'createHostingCluster'])->name('hosting-clusters.store');
    Route::put('hosting-clusters/{id}', [ApiClusterController::class, 'updateHostingCluster'])->name('hosting-clusters.update');
    Route::delete('hosting-clusters/{id}', [ApiClusterController::class, 'deleteHostingCluster'])->name('hosting-clusters.destroy');
    Route::get('hosting-cluster-dropdowns', [ApiClusterController::class, 'getHostingClusterDropdowns'])->name('hosting-clusters.dropdown');

    // System domain routes
    Route::post('check-system-domains', [ApiSystemDomainController::class, 'checkSystemDomainExist'])->name('check-system-domain.index');
    Route::get('system-domains', [ApiSystemDomainController::class, 'listSystemDomain'])->name('system-domain.index');
    Route::post('system-domains', [ApiSystemDomainController::class, 'createSystemDomain'])->name('system-domain.store');
    Route::put('system-domains/{id}', [ApiSystemDomainController::class, 'updateSystemDomain'])->name('system-domain.update');
    Route::delete('system-domains/{id}', [ApiSystemDomainController::class, 'deleteSystemDomain'])->name('system-domain.destroy');
    Route::get('system-domain-dropdowns', [ApiSystemDomainController::class, 'listSystemDomainDropdowns'])->name('system-domain.dropdown');

    // Settings routes
    Route::get('setting-provider-domain', [ApiSettingController::class, 'getSettingProviderDomain'])->name('settings.index');

    // Temporary domain routes
    Route::get('manage-temporary-domain', [ApiTemporaryDomainController::class, 'getManageTemporaryDomain'])->name('temporary-domains.manage');
    Route::post('temporary-domain-with-system-domain', [ApiTemporaryDomainController::class, 'getAllTemporaryDomainWithSystemDomain'])->name('temporary-domains.index');
    Route::put('temporary-domain-with-system-domain', [ApiTemporaryDomainController::class, 'updateTemporaryDomainWithSystemDomain'])->name('temporary-domains.update');
    Route::post('check-status-temporary-domain', [ApiTemporaryDomainController::class, 'checkStatusTemporaryDomain'])->name('temporary-domains.check-status');
    Route::post('add-more-temporary-domain', [ApiTemporaryDomainController::class, 'addMoreTemporaryDomain'])->name('temporary-domains.add-more');

    // Domain routes
    Route::get('domain', [ApiDomainController::class, 'getDomains'])->name('domain.index');
});
