<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ServiceProviderController;

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

Route::get('/', function () {
    return redirect()->route('home');
});

Route::get('/redirect', function () {
    if (\App\Helpers\UserTypeHelper::check() === "1") {
        return redirect()->route('adminHome');
    } else {
        return redirect()->route('serviceProvider.home');
    }
})->name('redirect');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::post('/login-user', [App\Http\Controllers\Auth\LoginController::class, 'loginUser'])->name('login.user');


//grouping routes for admin and service_providers
Route::group(['middleware' => 'auth'], function () {
    Route::group(['prefix' => 'admin'], function () {
    Route::get('home', [App\Http\Controllers\HomeController::class, 'adminIndex'])->name('adminHome');
    Route::resource('service-providers', ServiceProviderController::class);
    });

    Route::group(['prefix' => 'service-provider'], function () {
        Route::get('home', [App\Http\Controllers\ServiceProvider\DashboardController::class, 'home'])->name('serviceProvider.home');
        Route::resource('categories', App\Http\Controllers\ServiceProvider\CategoryController::class);
        Route::resource('items', App\Http\Controllers\ServiceProvider\ItemController::class);
        Route::resource('notifications', \App\Http\Controllers\ServiceProvider\NotificationController::class)->only(['index', 'show']);
    });

    Route::resource('uploader', App\Http\Controllers\UploadController::class);
});
