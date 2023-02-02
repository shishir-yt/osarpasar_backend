<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

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

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::group(['prefix' => '', 'middleware' => 'auth:api'], function () {
    // Route::get('/service-providers', [App\Http\Controllers\Api\ServiceProviderApiController::class, "getServiceProviders"]);
//     Route::get('/items', [App\Http\Controllers\Api\ItemApiController::class, "getItems"]);
// Route::get('/categories', [App\Http\Controllers\Api\ItemApiController::class, "getCategories"]);
Route::post('/other-items/store', [App\Http\Controllers\Api\ItemApiController::class, "addOtherItems"]);
Route::post('/orders/store', [App\Http\Controllers\Api\ItemApiController::class, "storeOrder"]);
// Route::get('address', [App\Http\Controllers\Api\AddressApiController::class, "getAddress"]);
});
Route::get('/service-providers', [App\Http\Controllers\Api\ServiceProviderApiController::class, "getServiceProviders"]);
Route::get('/items', [App\Http\Controllers\Api\ItemApiController::class, "getItems"]);
Route::get('/categories', [App\Http\Controllers\Api\ItemApiController::class, "getCategories"]);
Route::get('address', [App\Http\Controllers\Api\AddressApiController::class, "getAddress"]);
Route::post('order-address', [App\Http\Controllers\Api\AddressApiController::class, "storeOrderAddress"]);