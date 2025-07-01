<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\BillController;
use App\Http\Controllers\CompanyDetailController;
use App\Http\Controllers\PurchaseBillController;

// Default welcome route
Route::get('/', function () {
    return view('welcome');
});

// Dashboard route
Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');


// All application routes
Route::resource('suppliers', SupplierController::class);
Route::resource('medicines', MedicineController::class);
Route::resource('customers', CustomerController::class);
Route::resource('bills', BillController::class);
Route::resource('purchase-bills', PurchaseBillController::class); // <-- This line is now fixed


Route::get('company-details', [CompanyDetailController::class, 'edit'])->name('company-details.edit');
Route::put('company-details', [CompanyDetailController::class, 'update'])->name('company-details.update');


// Routes for user profile management (from Laravel Breeze)
Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


// Authentication routes (from Laravel Breeze)
// The line below is commented out because the auth.php file is missing.
// require __DIR__.'/auth.php';