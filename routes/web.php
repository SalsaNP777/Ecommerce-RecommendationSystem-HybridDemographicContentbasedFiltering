<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RatingController;
use App\Models\Rating;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

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
    return Redirect::route('index_product');
});

Auth::routes();

//guest
Route::get('/product', [ProductController::class, 'index_product'])->name('index_product');
// Route::get('/product/{product}/', [ProductController::class, 'show_product'])->name('show_product');
// Route::get('/product/{product}/', [ProductController::class, 'show_product_guest'])->name('show_product_guest');

//admin
Route::middleware(['admin'])->group(function(){
    Route::get('/product/create', [ProductController::class, 'create_product'])->name('create_product');
    Route::post('/product/create', [ProductController::class, 'store_product'])->name('store_product');
    Route::get('/product/{product}/edit', [ProductController::class, 'edit_product'])->name('edit_product');
    Route::patch('/product/{product}/update', [ProductController::class, 'update_product'])->name('update_product');
    Route::delete('product/{product}', [ProductController::class, 'delete_product'])->name('delete_product');
    Route::post('/order/{order}/confirm', [OrderController::class, 'confirm_payment'])->name('confirm_payment');
});

//user::Auth()
Route::middleware(['auth'])->group(function(){
    Route::get('/product/{product}/', [ProductController::class, 'show_product'])->name('show_product');
    Route::post('/cart/{product}', [CartController::class, 'add_to_cart'])->name('add_to_cart');
    Route::get('/cart', [CartController::class, 'show_cart'])->name('show_cart');
    Route::patch('/cart/{cart}',[CartController::class, 'update_cart'])->name('update_cart');
    Route::delete('cart/{cart}', [CartController::class, 'delete_Cart'])->name('delete_cart');
    Route::post('/checkout', [OrderController::class, 'checkout'])->name('checkout');
    Route::get('/order', [OrderController::class, 'index_order'])->name('index_order');
    Route::get('/order/{order}', [OrderController::class, 'show_order'])->name('show_order');
    Route::post('/order/{order}/pay', [OrderController::class, 'submit_payment_receipt'])->name('submit_payment_receipt');
    // Route::get('/product/{product}/{order}/{transaction}/create_rating', [RatingController::class, 'create_rating'])->name('create_rating');
    // Route::get('/rating/{product}/{order}/{transaction}/create_rating', [RatingController::class, 'create_rating'])->name('create_rating');
    // Route::get('/order/{order}/{transaction}/{product}/create_rating', [RatingController::class, 'create_rating'])->name('create_rating');
    // Route::get('rating/create_rating', [RatingController::class, 'create_rating'])->name('create_rating');
    // Route::get('/rating/{rating}/create_rating', [RatingController::class, 'create_rating'])->name('create_rating');
    // Route::get('/order/{order}/{transaction}/{product}/edit_rating', [RatingController::class, 'edit_rating'])->name('edit_rating');
    Route::post('/product/{product}/{order}/{transaction}', [ProductController::class, 'submit_rating'])->name('submit_rating');
    // Route::post('/rating/{product}/{order}/{transaction}', [RatingController::class, 'submit_rating'])->name('submit_rating');
    // Route::post('rating/{order}/{transaction}/{product}', [RatingController::class, 'submit_rating'])->name('submit_rating');
    // Route::post('rating/submit_rating', [RatingController::class, 'submit_rating'])->name('submit_rating');
    // Route::post('/rating/{product}/{order}/{transaction}', [ProductController::class, 'submit_rating'])->name('submit_rating');
    Route::patch('/product/{product}/{order}/{transaction}', [ProductController::class, 'update_rating'])->name('update_rating');
    Route::get('/profile', [ProfileController::class, 'show_profile'])->name('show_profile');
    Route::post('/profile', [ProfileController::class, 'edit_profile'])->name('edit_profile');
});


