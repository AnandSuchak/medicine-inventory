<?php

namespace App\Repositories\Eloquent;

use App\Models\PurchaseBillMedicine;
use App\Repositories\Interfaces\PurchaseBillMedicineRepositoryInterface;

class PurchaseBillMedicineRepository implements PurchaseBillMedicineRepositoryInterface
{
    // As with the interface, the main logic for adding/updating medicines
    // is now part of the PurchaseBillRepository to ensure it all happens
    // within a single database transaction when a bill is saved.
}