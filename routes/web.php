<?php

use App\Http\Controllers\front\CartController;
use App\Http\Controllers\Front\CheckoutController;
use App\Http\Controllers\Front\HomeController;
use App\Http\Controllers\Front\ProductsController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/products', [ProductsController::class, 'index'])->name('products.index');

// {product:slug} to use slug not id in route model binging, becuase i want pass the slug as parameter in the route
Route::get('/products/{product:slug}', [ProductsController::class, 'show'])->name('products.show');

Route::resource('cart', CartController::class);

Route::get('checkout', [CheckoutController::class,'create'])->name('checkout');
Route::post('checkout', [CheckoutController::class, 'store']);

// this is the defualt route for page so i require all defined routes
require __DIR__.'/auth.php'; //  __DIR__: magic constant
require __DIR__.'/dashboard.php';
