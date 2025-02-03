<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\YourDashboardController;
use App\Http\Controllers\AuthController; // We'll create this controller
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductInController;
use App\Http\Controllers\ProductOutController;
use App\Http\Controllers\ReportController; // Create this controller
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', function () {
    return view('login');
});

Route::get('/signup', function () {
    return view('signup');
});

Route::get('/products', function () {
    return view('products');
});


Route::get('/reports', function () {
    return view('reports');
});

Route::get('/signup', [AuthController::class, 'showSignupForm'])->name('signup');
Route::post('/signup', [AuthController::class, 'signup'])->name('signup.submit');

// Add your login route if you don't have it already.  We'll redirect to this route.
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit'); // New route for login submission


//Auth::routes();
// Dashboard route (protected by auth middleware)
 // Replace YourDashboardController
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');





    Route::post('/products', [ProductController::class, 'create'])->name('products.create');
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/{id}/edit', [ProductController::class, 'edit'])->name('products.edit'); // For fetching product data
    Route::put('/products/{id}', [ProductController::class, 'update'])->name('products.update'); // For updating the product
    Route::delete('/products/{id}', [ProductController::class, 'destroy'])->name('products.destroy'); // For deleting the product


    Route::post('/productin', [ProductInController::class, 'create'])->name('productin.create');
    Route::get('/stockin', [ProductInController::class, 'index'])->name('productin');

    Route::post('/productout', [ProductOutController::class, 'create'])->name('productout.create');
    Route::get('/stockout', [ProductOutController::class, 'index'])->name('productout');




    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::post('/reports/generate', [ReportController::class, 'generate'])->name('reports.generate'); // New route

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');


    // Product In routes
    Route::get('/product_ins/{id}/edit', [ProductInController::class, 'edit'])->name('product_ins.edit');
    Route::put('/product_ins/{id}', [ProductInController::class, 'update'])->name('product_ins.update');
    Route::delete('/product_ins/{id}', [ProductInController::class, 'destroy'])->name('product_ins.destroy');

    // Product Out routes
    Route::get('/product_outs/{id}/edit', [ProductOutController::class, 'edit'])->name('product_outs.edit');
    Route::put('/product_outs/{id}', [ProductOutController::class, 'update'])->name('product_outs.update');
    Route::delete('/product_outs/{id}', [ProductOutController::class, 'destroy'])->name('product_outs.destroy');