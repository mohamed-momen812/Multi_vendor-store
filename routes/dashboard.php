<?php

use App\Http\Controllers\Dashboard\CategoriesController;
use App\Http\Controllers\Dashboard\ProductsController;
use App\Http\Controllers\Dashboard\ProfileController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Middleware\CheckUserType;
use Illuminate\Support\Facades\Route;

// here i allaw only admins to access dashboard, and handel fortify config in fortifyServiceProvider

Route::group([ // using middleware auth => using default gaurd web
    // 'middleware' => ['auth', CheckUserType::class . ':admin,super-admin'], //  passing argument to CheckUserType middleware
    'middleware' => ['auth:admin'],
    'as' => 'dashboard.', // prefix name for all routes
    'prefix' => 'admin/dashboard' // prefix uri for all routes
], function () {

    // Route-based methods (Route::get()): Laravel automatically instantiates the controller, so methods can (and usually should) be non-static.
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::get('/', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::get('/categories/trash', [CategoriesController::class,'trash'])
        ->name('categories.trash');

    Route::put('/categories/{category}/restore', [CategoriesController::class,'restore'])
        ->name('categories.restore');

    Route::delete('/categories/{category}/force-delete', [CategoriesController::class,'forceDelete'])
        ->name('categories.force-delete');

    // Route::resource() return the 7 resources routes with names
    Route::resource('/categories', CategoriesController::class);
    Route::resource('/products', ProductsController::class);
});




