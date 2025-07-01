<?php

namespace App\Repositories\Eloquent;

use App\Models\Medicine;
use App\Repositories\Interfaces\StockRepositoryInterface;
use Illuminate\Support\Facades\DB; // Import DB facade for raw queries

class StockRepository implements StockRepositoryInterface
{
    /**
     * Get a list of all medicines with their total aggregated stock quantity.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getTotalStockItems()
    {
        // This query sums up the quantity of each medicine across all batches
        // and groups them by medicine details.
        return Medicine::select(
                'medicines.id',
                'medicines.name',
                'medicines.unit',
                'medicines.description' // Include description if you want to display it
            )
            // Left join ensures that medicines with zero stock (not in any batch_medicine) are also included
            ->leftJoin('batch_medicine', 'medicines.id', '=', 'batch_medicine.medicine_id')
            // COALESCE(SUM(...), 0) ensures that if a medicine has no batch_medicine entries, its total_stock_quantity is 0 instead of NULL
            ->selectRaw('COALESCE(SUM(batch_medicine.quantity), 0) as total_stock_quantity')
            ->groupBy('medicines.id', 'medicines.name', 'medicines.unit', 'medicines.description') // Group by all selected non-aggregated columns
            ->orderBy('medicines.name')
            ->get();
    }

    /**
     * Get details for a specific medicine, including its batches and quantities.
     *
     * @param  Medicine $medicine
     * @return Medicine
     */
    public function getMedicineStockDetails(Medicine $medicine)
    {
        // Eager load the 'batches' relationship for the specific medicine.
        // We also order the batches for consistency.
        // We can re-calculate total stock here for the show page if needed,
        // or just rely on the sum of quantities in the batches.
        $medicine->load([
            'batches' => function ($query) {
                // Select pivot data directly for use in view, and order batches
                $query->select('batches.id', 'batches.batch_number', 'batches.purchase_date')
                      ->withPivot('quantity', 'price', 'ptr', 'gst_percent', 'expiry_date')
                      ->orderBy('batches.purchase_date', 'desc');
            }
        ]);

        return $medicine;
    }
}