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
Route::prefix('bills')->name('bills.')->group(function () {
    Route::get('/', [BillController::class, 'index'])->name('index');
    Route::get('/create', [BillController::class, 'create'])->name('create');
    Route::post('/', [BillController::class, 'store'])->name('store');
    Route::get('/{bill}', [BillController::class, 'show'])->name('show');

    // NEW: Edit and Update routes for BillController
    Route::get('/{bill}/edit', [BillController::class, 'edit'])->name('edit');
    Route::put('/{bill}', [BillController::class, 'update'])->name('update');

    // API Routes for Bills
    Route::get('/api/medicine-stock-info/{medicineId}', [BillController::class, 'getMedicineStockInfo'])->name('api.medicine_stock_info');
    Route::get('/api/medicine-search', [BillController::class, 'searchMedicines'])->name('api.medicine_search');
    Route::get('/api/customer-search', [BillController::class, 'searchCustomers'])->name('api.customer_search');
});

// Stocks (Custom Routes)
Route::get('/stocks', [App\Http\Controllers\StockController::class, 'index'])->name('stocks.index');
Route::get('/stocks/{medicine}', [App\Http\Controllers\StockController::class, 'show'])->name('stocks.show');
Route::get('/stock', [BatchController::class, 'indexStock'])->name('stock.index'); // Legacy stock index (if needed)

// web.php (Duplicate - assuming this was leftover from previous snippets)
Route::get('/get-batches/{medicine}', [BatchController::class, 'getByMedicine']);
