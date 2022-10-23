<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApplicationController;

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
//Route::get('/{any}', [ApplicationController::class, 'index'])->where('any', '.*');

//Route::middleware('web')->domain('admin.'.env('SITE_URL'))->group(function () {
//    Route::get('/{any}', [ApplicationController::class, 'index'])->where('any', '.*');
//});

//coding (notfinished) for multi sub domains
//Route::middleware('web')->domain(env('SITE_URL'))->group(function () {
//    Route::get('/', ['as' => 'homepage', 'uses' => 'App\Http\Controllers\PageController@homepage'])->name('homepage');
//    Route::get('/services', ['as' => 'services', 'uses' => 'App\Http\Controllers\PageController@services'])->name('services');
//    Route::get('/aboutus', ['as' => 'aboutus', 'uses' => 'App\Http\Controllers\PageController@aboutus'])->name('aboutus');
//    Route::get('/support', ['as' => 'support', 'uses' => 'App\Http\Controllers\PageController@support'])->name('support');
//    Route::get('/partners', ['as' => 'partners', 'uses' => 'App\Http\Controllers\PageController@partners'])->name('partners');
//    Route::get('/contact', ['as' => 'contact', 'uses' => 'App\Http\Controllers\PageController@contact'])->name('contact');
//});
//======================================


Route::get('/', ['as' => 'homepage', 'uses' => 'App\Http\Controllers\PageController@homepage'])->name('homepage');
Route::get('/services', ['as' => 'services', 'uses' => 'App\Http\Controllers\PageController@services'])->name('services');
Route::get('/aboutus', ['as' => 'aboutus', 'uses' => 'App\Http\Controllers\PageController@aboutus'])->name('aboutus');
Route::get('/support', ['as' => 'support', 'uses' => 'App\Http\Controllers\PageController@support'])->name('support');
Route::get('/partners', ['as' => 'partners', 'uses' => 'App\Http\Controllers\PageController@partners'])->name('partners');
Route::get('/contact', ['as' => 'contact', 'uses' => 'App\Http\Controllers\PageController@contact'])->name('contact');

//route for dashboard app, frefix '/admin'
Route::group(['prefix' => 'admin'], function () {
    Route::get('/', [ApplicationController::class, 'index'])->where('any', '.*');
    Route::get('/{any}', [ApplicationController::class, 'index'])->where('any', '.*');
});

