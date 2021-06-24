<?php

use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\SearchController;
use App\Http\Controllers\Ecommerce\Auth\EmailVerificationController;
use App\Http\Controllers\Ecommerce\Auth\LoginController;
use App\Http\Controllers\Ecommerce\Auth\LogoutController;
use App\Http\Controllers\Ecommerce\Auth\RegisterController;
use App\Http\Controllers\Ecommerce\Auth\ResetPasswordController;
use App\Http\Controllers\Ecommerce\FrontController;
use App\Http\Controllers\Ecommerce\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/// Admin Routes
Route::get('/search', [SearchController::class, 'index'])->name('product.search');
//? Auth admin
Route::group(['prefix' => 'admin'], function () {
	// Authentication Routes...
	Route::get('login', 'App\Http\Controllers\Auth\LoginController@showLoginForm')->name('login.admin');
	Route::post('login', 'App\Http\Controllers\Auth\LoginController@login');
	Route::get('logout', 'App\Http\Controllers\Auth\LogoutController@logout')->name('logout.admin');

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
	Route::put('profile/password', ['as' => 'profile.password', 'uses' => 'App\Http\Controllers\ProfileController@password']);

	// Category route
	Route::resource('category', 'App\Http\Controllers\Admin\CategoryController', ['except' => ['show', 'create']]);

	// Merk Route
	Route::resource('merk', 'App\Http\Controllers\Admin\MerkController')->except(['show', 'create']);

	// Product Route
	Route::resource('product', ProductController::class);
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

Route::group(['middleware' => 'auth:customer', 'prefix' => 'profile'], function () {
	// Edit user info
	Route::get('', [ProfileController::class, 'show'])->name('profile.user');
	Route::patch('', [ProfileController::class, 'userEdit'])->name('profile.user-edit');
	// Change password
	Route::get('/change-password', [ProfileController::class, 'userPassword'])->name('profile.password-edit');
	Route::patch('/change-password', [ProfileController::class, 'userPasswordUpdate'])->name('profile.password-update');
	// Address book route
	Route::group(['prefix' => 'address-book'], function () {
		Route::get('/', [ProfileController::class, 'showAddress'])->name('profile.address');
		Route::get('/add', [ProfileController::class, 'showAddressForm'])->name('profile.address-form');
		Route::post('/', [ProfileController::class, 'addressHandle'])->name('profile.address-add');
		Route::get('/{id}/edit', [ProfileController::class, 'edit'])->name('profile.address-edit');
		Route::patch('/{id}/edit', [ProfileController::class, 'update'])->name('profile.address-update');
		Route::delete('/{id}/delete', [ProfileController::class, 'destroy'])->name('profile.address-destroy');
	});
});
	
//! End of User Auth Routes