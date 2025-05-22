<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\BillController;
use App\Http\Controllers\BatchController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\BatchMedicineController;
use App\Http\Controllers\StockController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Dashboard
Route::get('/', function () {
    return view('dashboard');
})->name('dashboard');

// Batches CRUD (Except Show)
Route::resource('batches', BatchController::class)->except(['show']);

// Show Batch Details
Route::get('batches/{batch}', [BatchController::class, 'show'])->name('batches.show');

// Batch Medicines Nested under Batches
Route::prefix('batches/{batch}')->name('batches.')->group(function () {
    // Add Medicines to Batch
    Route::get('medicines/create', [BatchMedicineController::class, 'create'])->name('medicines.create');
    Route::post('medicines', [BatchMedicineController::class, 'store'])->name('medicines.store');

    // Edit Medicines in Batch
    Route::get('medicines/edit', [BatchMedicineController::class, 'edit'])->name('medicines.edit');
    Route::put('medicines', [BatchMedicineController::class, 'update'])->name('medicines.update');

    // Remove Medicine from Batch
    Route::delete('medicines/{medicine}', [BatchMedicineController::class, 'remove'])->name('medicines.remove');
});

// Medicines CRUD
Route::resource('medicines', MedicineController::class);

// Suppliers CRUD
Route::resource('suppliers', SupplierController::class);

// Customers CRUD
Route::resource('customers', CustomerController::class);

// Bills CRUD
Route::resource('bills', BillController::class);

// Stocks (Custom Routes)
Route::get('/stock', [BatchController::class, 'indexStock'])->name('stock.index'); // Legacy stock index (if needed)
Route::get('/stocks', [StockController::class, 'index'])->name('stocks.index');    // New stock index
Route::get('/stocks/{medicine}', [StockController::class, 'show'])->name('stocks.show');
// web.php
Route::get('/get-batches/{medicine}', [BatchController::class, 'getByMedicine']);
