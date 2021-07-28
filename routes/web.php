<?php

use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\SearchController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\Ecommerce\Auth\EmailVerificationController;
use App\Http\Controllers\Ecommerce\Auth\LoginController;
use App\Http\Controllers\Ecommerce\Auth\LogoutController;
use App\Http\Controllers\Ecommerce\Auth\RegisterController;
use App\Http\Controllers\Ecommerce\Auth\ResetPasswordController;
use App\Http\Controllers\Ecommerce\CartController;
use App\Http\Controllers\Ecommerce\FrontController;
use App\Http\Controllers\Ecommerce\OrderController;
use App\Http\Controllers\Ecommerce\ProfileController;
use App\Http\Controllers\Ecommerce\WishlistController;
use App\Http\Controllers\InventoryController;
use Illuminate\Support\Facades\Route;

/*
  |--------------------------------------------------------------------------
  |--------------------------------------------------------------------------
  | Route Halaman Admin
*/

Route::get('/search', [SearchController::class, 'index'])->name('product.search');

/*
  |--------------------------------------------------------------------------
  | Authentication Route Admin
*/
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
/*
	| Authentication Route Admin
	|--------------------------------------------------------------------------
*/

Route::group(['middleware' => 'auth', 'prefix' => 'admin'], function () {


	Route::get('/', 'App\Http\Controllers\HomeController@index')->name('home');
	Route::resource('customer', 'App\Http\Controllers\CustomerController', ['except' => ['show']]);

	Route::post('customer/deleted/{id}', [CustomerController::class, 'force'])->name('customer.force-delete');
	Route::post('customer/restore/{id}', [CustomerController::class, 'restore'])->name('customer.restore');
	Route::get('customer/deleted', [CustomerController::class, 'deletedUser'])->name('customer.deleted');
	Route::post('customer/delete-all', [CustomerController::class, 'deleteAll'])->name('customer.delete-all');
	Route::post('customer/restore-all', [CustomerController::class, 'restoreAll'])->name('customer.restore-all');

	Route::get('profile', ['as' => 'profile.edit', 'uses' => 'App\Http\Controllers\ProfileController@edit']);
	Route::put('profile', ['as' => 'profile.update', 'uses' => 'App\Http\Controllers\ProfileController@update']);
	Route::put('profile/password', ['as' => 'profile.password', 'uses' => 'App\Http\Controllers\ProfileController@password']);

	// Category Routes...
	Route::resource('category', 'App\Http\Controllers\Admin\CategoryController', ['except' => ['show', 'create']]);

	// Merk Routes...
	Route::resource('merk', 'App\Http\Controllers\Admin\MerkController')->except(['show', 'create']);

	// Product Routes...
	Route::resource('product', ProductController::class);
	Route::post('product/bulk', [ProductController::class, 'massUpload'])->name('product.bulk');

	// Inventory Routes...
	Route::get('inventory/getsales/{product:id}', [InventoryController::class, 'getSales'])->name('inventory.get-sales');
	Route::get('inventory/gethistories/{product:id}', [InventoryController::class, 'getHistories'])->name('inventory.get-histories');
	Route::get('inventory', [InventoryController::class, 'index'])->name('inventory.index');
	Route::get('inventory/{key}/summary', [InventoryController::class, 'adjustSummary'])->name('inventory.adjust-summary');
	Route::get('inventory/adjust', [InventoryController::class, 'adjust'])->name('inventory.adjust');
	Route::post('inventory/adjust', [InventoryController::class, 'update'])->name('inventory.update');
	Route::get('inventory/{product:slug}/sales', [InventoryController::class, 'showSales'])->name('inventory.history-sales');

	Route::get('inventory/{product:slug}/adj', [InventoryController::class, 'showHistories'])->name('inventory.history-adj');

	// Orders Routes...
	Route::group(['prefix' => 'orders'], function () {
		// Order Reports...
		Route::get('/report', [AdminOrderController::class, 'orderReport'])->name('orders.report');
		Route::get('/report/pdf/{daterange}/{title}', [AdminOrderController::class, 'orderReportPdf'])->name('report.order-pdf');
		Route::get('/report-return', [AdminOrderController::class, 'orderReturnReport'])->name('orders.report-return');

		// Order Management...
		Route::get('/', [AdminOrderController::class, 'index'])->name('orders.index');
		Route::get('/deleted', [AdminOrderController::class, 'showDeleted'])->name('orders.deleted');
		Route::get('/{invoice}', [AdminOrderController::class, 'show'])->name('orders.show');
		Route::post('/deleted/{id}', [AdminOrderController::class, 'forceDestroy'])->name('orders.force-destroy');
		Route::post('/{order:id}', [AdminOrderController::class, 'destroy'])->name('orders.destroy');
		Route::post('/restore/{id}', [AdminOrderController::class, 'restore'])->name('orders.restore');
		Route::get('/payment/{order:invoice}', [AdminOrderController::class, 'acceptPayment'])->name('orders.approve-payment');
		Route::post('/shipping', [AdminOrderController::class, 'shippingOrder'])->name('orders.shipping');

		Route::get('/return/{order:invoice}', [AdminOrderController::class, 'returnShow'])->name('orders.return');
		Route::post('/return/{value}', [AdminOrderController::class, 'confirmReturn'])->name('orders.approve-return');
	});
});
/*
  | Route Halaman Admin
  |--------------------------------------------------------------------------
  |--------------------------------------------------------------------------
*/


/*
	|--------------------------------------------------------------------------
	|--------------------------------------------------------------------------
  | Route Halaman Customer / User
*/

/*
	|--------------------------------------------------------------------------
  | Authentication & Profile Routes Customer / User
*/
Route::group(['middleware' => 'guest:customer'], function () {
	// Register Routes...
	Route::get('register', [RegisterController::class, 'show'])->name('register');
	Route::post('register', [RegisterController::class, 'handle'])->name('register');

	// Login Routes...
	Route::get('login', [LoginController::class, 'show'])->name('login');
	Route::post('login', [LoginController::class, 'handle'])->name('login');

	// Forgot Password Routes...
	Route::get('forgot-password', [ResetPasswordController::class, 'show'])->name('password.request');
	Route::post('forgot-password/request', [ResetPasswordController::class, 'request'])->name('password.email');
	Route::get('forgot-password/{token}', [ResetPasswordController::class, 'formReset'])->name('password.reset');
	Route::post('reset-password', [ResetPasswordController::class, 'update'])->name('password.update');
});

// Logout Route...
Route::post('logout', [LogoutController::class, 'handle'])->name('logout');

// Verify Email Routes...
Route::group(['middleware' => 'auth:customer'], function () {
	Route::post('verify-email/request', [EmailVerificationController::class, 'request'])->middleware('auth:customer')->name('verification.request');
	Route::get('verify/email', [EmailVerificationController::class, 'show'])->name('verification.notice');
	Route::get('verify-email/{id}/{hash}', [EmailVerificationController::class, 'verify'])->middleware('auth:customer', 'signed')->name('verification.verify');
});

// Customer / User Profile Routes...
Route::group(['middleware' => 'auth:customer', 'prefix' => 'profile'], function () {
	// Edit user info...
	Route::get('', [ProfileController::class, 'show'])->name('profile.user');
	Route::patch('', [ProfileController::class, 'userEdit'])->name('profile.user-edit');
	// Change password...
	Route::get('/change-password', [ProfileController::class, 'userPassword'])->name('profile.password-edit');
	Route::patch('/change-password', [ProfileController::class, 'userPasswordUpdate'])->name('profile.password-update');
	// Address book routes...
	Route::group(['prefix' => 'address-book'], function () {
		Route::get('/', [ProfileController::class, 'showAddress'])->name('profile.address');
		Route::get('/add', [ProfileController::class, 'showAddressForm'])->name('profile.address-form');
		Route::post('/', [ProfileController::class, 'addressHandle'])->name('profile.address-add');
		Route::get('/{addressBook:id}/edit', [ProfileController::class, 'edit'])->name('profile.address-edit');
		Route::patch('/{addressBook:id}/edit', [ProfileController::class, 'update'])->name('profile.address-update');
		Route::delete('/{addressBook:id}/delete', [ProfileController::class, 'destroy'])->name('profile.address-destroy');
	});
});

/*
	| Authentication Routes Customer / User
	|--------------------------------------------------------------------------
*/

// Front Page Routes...
Route::get('/', [FrontController::class, 'index'])->name('front');
Route::get('/product', [FrontController::class, 'product'])->name('front.product');
Route::get('/category/{category:slug}', [FrontController::class, 'categoryProduct'])->name('front.category');
Route::get('/product/{product:slug}', [FrontController::class, 'show'])->name('front.show');

// Cart routes...
Route::group(['middleware' => ['auth:customer', 'verified'], 'prefix' => 'cart'], function () {
	Route::get('/', [CartController::class, 'show'])->name('cart.show');
	Route::post('/add', [CartController::class, 'addToCart'])->name('cart.add');
	Route::post('/update', [CartController::class, 'updateCart'])->name('cart.update');
	Route::post('/empty', [CartController::class, 'emptyCart'])->name('cart.empty');
	Route::get('/destroy/{id}', [CartController::class, 'destroy'])->name('cart.destroy');
	// Checkout Routes...
	Route::get('/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
	Route::post('/checkout', [CartController::class, 'processCheckout'])->name('cart.process-checkout');
	Route::get('/checkout/order/{order:invoice}', [CartController::class, 'checkoutFinish'])->name('cart.finish');
});

// Wishlist routes...
Route::group(['middleware' => 'auth:customer'], function () {
	Route::get('wishlist', [WishlistController::class, 'show'])->name('wishlist.show');
	Route::post('wishlist', [WishlistController::class, 'addToWishlist'])->name('wishlist.add');
	Route::post('wishlist/destroy/{wishlist:id}', [WishlistController::class, 'destroy'])->name('wishlist.destroy');
});

// Order routes...
Route::group(['prefix' => 'order', 'middleware' => 'auth:customer'], function () {
	Route::get('dashboard', [OrderController::class, 'dashboard'])->name('order.dashboard');
	Route::get('a-payment', [OrderController::class, 'awaitingPayment'])->name('order.a-payment');
	Route::get('a-confirm', [OrderController::class, 'awaitingConfirm'])->name('order.a-confirm');
	Route::get('process', [OrderController::class, 'process'])->name('order.process');
	Route::get('sent', [OrderController::class, 'sent'])->name('order.sent');
	Route::get('done', [OrderController::class, 'done'])->name('order.done');
	Route::get('/{order:invoice}', [OrderController::class, 'show'])->name('order.show');

	//Payment Routes...
	Route::get('/confirm-payment/{order:invoice}', [OrderController::class, 'paymentForm'])->name('payment.form');
	Route::post('/confirm-payment', [OrderController::class, 'savePayment'])->name('payment.save');
	Route::get('/pdf/{order:invoice}', [OrderController::class, 'pdf'])->name('order.show-pdf');
	Route::patch('/accept/{order:id}', [OrderController::class, 'acceptOrder'])->name('order.accept');

	// Return Order Routes...
	Route::get('/return/{order:invoice}', [OrderController::class, 'returnForm'])->name('order.return-form');
	Route::post('/return/{order:invoice}', [OrderController::class, 'returnProcess'])->name('order.return');
});

/*
	| Route Halaman Customer / User
	|--------------------------------------------------------------------------
	|--------------------------------------------------------------------------
*/

Route::get('test', [AdminOrderController::class, 'test']);
