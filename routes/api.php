<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Ecommerce\CartController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\RegionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('city', [RegionController::class, 'getCity']);
Route::get('district', [RegionController::class, 'getDistrict']);
Route::get('address', [RegionController::class, 'getAddress']);
Route::post('cost', [CartController::class, 'getCourier']);

Route::get('type', [ProductController::class, 'type'])->name('type-catgories');
