<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\PurchaseBillController;
use App\Http\Controllers\BillController;
use App\Http\Controllers\CompanyDetailController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// The root URL now directly loads the dashboard view.
Route::get('/', function () {
    return view('dashboard');
})->name('dashboard');


// All application routes are now public.
// The 'auth' middleware group has been removed.

// Resourceful Routes
Route::resource('suppliers', SupplierController::class);
Route::resource('medicines', MedicineController::class);
Route::resource('customers', CustomerController::class);
Route::resource('bills', BillController::class);
Route::resource('purchase_bills', PurchaseBillController::class);

// Company Details Route
Route::get('company-details', [CompanyDetailController::class, 'edit'])->name('company_details.edit');
Route::patch('company-details', [CompanyDetailController::class, 'update'])->name('company_details.update');

// AJAX Search Routes
Route::get('/search-medicines', [BillController::class, 'searchMedicines'])->name('search.medicines');
Route::get('/search-customers', [BillController::class, 'searchCustomers'])->name('search.customers');
Route::get('/purchase-bills/search-medicines', [PurchaseBillController::class, 'searchMedicines'])->name('purchase_bills.search_medicines');