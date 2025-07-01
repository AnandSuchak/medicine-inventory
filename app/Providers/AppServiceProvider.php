<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Interfaces\SupplierRepositoryInterface;
use App\Repositories\Eloquent\SupplierRepository;
use App\Repositories\Interfaces\MedicineRepositoryInterface;
use App\Repositories\Eloquent\MedicineRepository;
use App\Repositories\Interfaces\CustomerRepositoryInterface;
use App\Repositories\Eloquent\CustomerRepository;
use App\Repositories\Interfaces\BatchRepositoryInterface;
use App\Repositories\Eloquent\BatchRepository;
use App\Repositories\Interfaces\BatchMedicineRepositoryInterface;
use App\Repositories\Eloquent\BatchMedicineRepository;
use App\Repositories\Interfaces\StockRepositoryInterface;
use App\Repositories\Eloquent\StockRepository;
use App\Repositories\Interfaces\BillRepositoryInterface;
use App\Repositories\Eloquent\BillRepository;

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
        $this->app->bind(BatchRepositoryInterface::class, BatchRepository::class);
        $this->app->bind(BatchMedicineRepositoryInterface::class, BatchMedicineRepository::class);
        $this->app->bind(StockRepositoryInterface::class, StockRepository::class);
        $this->app->bind(BillRepositoryInterface::class, BillRepository::class);
    }
    

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
