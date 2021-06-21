<?php

use App\Http\Controllers\Ecommerce\Auth\EmailVerificationController;
use App\Http\Controllers\Ecommerce\Auth\LoginController;
use App\Http\Controllers\Ecommerce\Auth\LogoutController;
use App\Http\Controllers\Ecommerce\Auth\RegisterController;
use App\Http\Controllers\Ecommerce\Auth\ResetPasswordController;
use App\Http\Controllers\Ecommerce\FrontController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

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

// Route::get('/', function () {
//     return view('welcome');
// });

// Auth::routes();

/// Admin Routes
Route::get('/search', 'App\Http\Controllers\SearchController@index')->name('product.search');
//? Auth admin
Route::group(['prefix' => 'admin'], function () {
	// Authentication Routes...
	Route::get('login', 'App\Http\Controllers\Auth\LoginController@showLoginForm')->name('login.admin');
	Route::post('login', 'App\Http\Controllers\Auth\LoginController@login');
	Route::post('logout', 'App\Http\Controllers\Auth\LoginController@logout')->name('logout.admin');

	// Registration Routes...
	Route::get('register', 'App\Http\Controllers\Auth\RegisterController@showRegistrationForm')->name('register.admin');
	Route::post('register', 'App\Http\Controllers\Auth\RegisterController@register');

	// Password Reset Routes...
	Route::get('password/reset', 'App\Http\Controllers\Auth\ForgotPasswordController@showLinkRequestForm');
	Route::post('password/email', 'App\Http\Controllers\Auth\ForgotPasswordController@sendResetLinkEmail');
	Route::get('password/reset/{token}', 'App\Http\Controllers\Auth\ResetPasswordController@showResetForm');
	Route::post('password/reset', 'App\Http\Controllers\Auth\ResetPasswordController@reset');
});
//? End of Auth Admin

Route::group(['middleware' => 'auth', 'prefix' => 'admin'], function () {

	Route::get('/', 'App\Http\Controllers\HomeController@index')->name('home');
	Route::resource('user', 'App\Http\Controllers\UserController', ['except' => ['show']]);
	Route::get('profile', ['as' => 'profile.edit', 'uses' => 'App\Http\Controllers\ProfileController@edit']);
	Route::put('profile', ['as' => 'profile.update', 'uses' => 'App\Http\Controllers\ProfileController@update']);

	// Category route
	Route::resource('category', 'App\Http\Controllers\CategoryController', ['except' => ['show', 'create']]);

	// Merk Route
	Route::resource('merk', 'App\Http\Controllers\MerkController')->except(['show', 'create']);

	// Product Route
	Route::resource('product', 'App\Http\Controllers\ProductController');
	Route::post('product/bulk', [ProductController::class, 'massUpload'])->name('product.bulk');
});
/// End Of Admin Routes

Route::get('/', [FrontController::class, 'index'])->name('front');
Route::get('/product', [FrontController::class, 'product'])->name('front.product');
Route::get('/category/{slug}', [FrontController::class, 'categoryProduct'])->name('front.category');
Route::get('/product/{product:slug}', [FrontController::class, 'show'])->name('front.show');

//! User Auth Routes

Route::group(['middleware' => 'guest:customer'], function () {
	/// register
	Route::get('register', [RegisterController::class, 'show'])->name('register');
	Route::post('register', [RegisterController::class, 'handle'])->name('register');

	/// login
	Route::get('login', [LoginController::class, 'show'])->name('login');
	Route::post('login', [LoginController::class, 'handle'])->name('login');

	/// forgot password
	Route::get('forgot-password', [ResetPasswordController::class, 'show'])->name('password.request');
	Route::post('forgot-password/request', [ResetPasswordController::class, 'request'])->name('password.email');
	Route::get('forgot-password/{token}', [ResetPasswordController::class, 'formReset'])->name('password.reset');
	Route::post('reset-password', [ResetPasswordController::class, 'update'])->name('password.update');
});

/// logout
Route::post('logout', [LogoutController::class, 'handle'])->name('logout');

/// verify
Route::group(['middleware' => 'auth:customer'], function () {
	Route::post('verify-email/request', [EmailVerificationController::class, 'request'])->middleware('auth:customer')->name('verification.request');

	Route::get('verify-email/{id}/{hash}', [EmailVerificationController::class, 'verify'])->middleware('auth:customer', 'signed')->name('verification.verify');
});

//! End of User Auth Routes