<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

// Interfaces
use App\Repositories\Interfaces\SupplierRepositoryInterface;
use App\Repositories\Interfaces\MedicineRepositoryInterface;
use App\Repositories\Interfaces\CustomerRepositoryInterface;
use App\Repositories\Interfaces\BillRepositoryInterface;
use App\Repositories\Interfaces\PurchaseBillRepositoryInterface;
use App\Repositories\Interfaces\PurchaseBillMedicineRepositoryInterface;

// Eloquent Repositories
use App\Repositories\Eloquent\SupplierRepository;
use App\Repositories\Eloquent\MedicineRepository;
use App\Repositories\Eloquent\CustomerRepository;
use App\Repositories\Eloquent\BillRepository;
use App\Repositories\Eloquent\PurchaseBillRepository;
use App\Repositories\Eloquent\PurchaseBillMedicineRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register()
    {
        $this->app->bind(SupplierRepositoryInterface::class, SupplierRepository::class);
        $this->app->bind(MedicineRepositoryInterface::class, MedicineRepository::class);
        $this->app->bind(CustomerRepositoryInterface::class, CustomerRepository::class);
        $this->app->bind(BillRepositoryInterface::class, BillRepository::class);

        // Renamed from Batch to PurchaseBill
        $this->app->bind(PurchaseBillRepositoryInterface::class, PurchaseBillRepository::class);
        $this->app->bind(PurchaseBillMedicineRepositoryInterface::class, PurchaseBillMedicineRepository::class);

        // The StockRepository is no longer needed with the new logic, so it has been removed.
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}