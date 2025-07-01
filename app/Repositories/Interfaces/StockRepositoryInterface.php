<?php

namespace App\Repositories\Interfaces;

use App\Models\Medicine; // Import Medicine model

interface StockRepositoryInterface
{
    /**
     * Get a list of all medicines with their total aggregated stock quantity.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getTotalStockItems();

    /**
     * Get details for a specific medicine, including its batches and quantities.
     *
     * @param  Medicine $medicine
     * @return Medicine
     */
    public function getMedicineStockDetails(Medicine $medicine);
}