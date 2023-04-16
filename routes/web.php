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
        Route::get('profile', [App\Http\Controllers\ServiceProvider\DashboardController::class, 'profile'])->name('serviceProvider.profile');
        Route::post('profile/store', [App\Http\Controllers\ServiceProvider\DashboardController::class, 'profileStore'])->name('serviceProvider.profile-store');
        Route::get('password-change', [\App\Http\Controllers\ServiceProvider\ChangePasswordController::class, 'changePassword'])->name('password.change');
    Route::post('password-change/store', [\App\Http\Controllers\ServiceProvider\ChangePasswordController::class, 'changePasswordSave'])->name('password-change.store');
        Route::resource('categories', App\Http\Controllers\ServiceProvider\CategoryController::class);
        Route::resource('notifications', \App\Http\Controllers\ServiceProvider\NotificationController::class)->only(['index', 'show']);
        Route::get('all-notifications', [\App\Http\Controllers\ServiceProvider\NotificationController::class, 'allNotifications'])->name('all.notifications');
        Route::resource('items', App\Http\Controllers\ServiceProvider\ItemController::class);
        Route::resource('payments', App\Http\Controllers\ServiceProvider\PaymentController::class);
        Route::get('order-details/{id}', [App\Http\Controllers\ServiceProvider\DashboardController::class, 'orderRequest'])->name('order.details');
        Route::post('response', [App\Http\Controllers\ServiceProvider\DashboardController::class, 'response'])->name('response.send');
        Route::get('success', function() {
            return view('service_providers.success');
        });
    });

    Route::resource('uploader', App\Http\Controllers\UploadController::class);
});