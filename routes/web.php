<?php

use App\Http\Controllers\Ecommerce\CartController;
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

//! Admin Routes
Route::get('/search', 'App\Http\Controllers\SearchController@index')->name('product.search');
//? Auth admin
Route::group(['prefix' => 'administrator'], function(){
	// Authentication Routes...
	Route::get('login', 'App\Http\Controllers\Auth\LoginController@showLoginForm')->name('login');
	Route::post('login', 'App\Http\Controllers\Auth\LoginController@login');
	Route::post('logout', 'App\Http\Controllers\Auth\LoginController@logout')->name('logout');

	// Registration Routes...
	Route::get('register', 'App\Http\Controllers\Auth\RegisterController@showRegistrationForm')->name('register');
	Route::post('register', 'App\Http\Controllers\Auth\RegisterController@register');

	// Password Reset Routes...
	Route::get('password/reset', 'App\Http\Controllers\Auth\ForgotPasswordController@showLinkRequestForm');
	Route::post('password/email', 'App\Http\Controllers\Auth\ForgotPasswordController@sendResetLinkEmail');
	Route::get('password/reset/{token}', 'App\Http\Controllers\Auth\ResetPasswordController@showResetForm');
Route::post('password/reset', 'App\Http\Controllers\Auth\ResetPasswordController@reset');
});
//? End of Auth Admin

Route::group(['middleware' => 'auth', 'prefix' => 'administrator'], function () {

	Route::get('/home', 'App\Http\Controllers\HomeController@index')->name('home');
	Route::resource('user', 'App\Http\Controllers\UserController', ['except' => ['show']]);
	Route::get('profile', ['as' => 'profile.edit', 'uses' => 'App\Http\Controllers\ProfileController@edit']);
	Route::put('profile', ['as' => 'profile.update', 'uses' => 'App\Http\Controllers\ProfileController@update']);
	Route::get('upgrade', function () {return view('pages.upgrade');})->name('upgrade'); 
	 Route::get('map', function () {return view('pages.maps');})->name('map');
	 Route::get('icons', function () {return view('pages.icons');})->name('icons'); 
	 Route::get('table-list', function () {return view('pages.tables');})->name('table');
	Route::put('profile/password', ['as' => 'profile.password', 'uses' => 'App\Http\Controllers\ProfileController@password']);

	// Category route
	Route::resource('category', 'App\Http\Controllers\CategoryController', ['except' => ['show', 'create']]);
	// Merk Route
	Route::resource('merk', 'App\Http\Controllers\MerkController')->except(['show','create']);
	// Product Route
	Route::resource('product', 'App\Http\Controllers\ProductController');
	Route::post('product/bulk', [ProductController::class, 'massUpload'])->name('product.bulk');
});
//! End Of Admin Routes

Route::get('/', [FrontController::class, 'index'])->name('front');
Route::get('/product', [FrontController::class, 'product'])->name('front.product');
Route::get('/category/{slug}', [FrontController::class, 'categoryProduct'])->name('front.category');
Route::get('/product/{product:slug}', [FrontController::class, 'show'])->name('front.show');

Route::post('/cart', [CartController::class, 'addToCart'])->name('front.cart');
Route::post('/cart/update', [CartController::class, 'updateCart'])->name('front.update_cart');
Route::get('/cart', [CartController::class, 'showCart'])->name('front.show_cart');