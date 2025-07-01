<?php

namespace App\Repositories\Eloquent;

use App\Models\Batch;
use App\Repositories\Interfaces\BatchMedicineRepositoryInterface;

class BatchMedicineRepository implements BatchMedicineRepositoryInterface
{
    public function attachMedicinesToBatch($batchId, array $medicines)
    {
        $batch = Batch::findOrFail($batchId);
        $syncData = [];

        foreach ($medicines as $medicine) {
            $syncData[$medicine['medicine_id']] = [
                'quantity' => $medicine['quantity'],
                'price' => $medicine['price'],
                'ptr' => $medicine['ptr'],
                'gst_percent' => $medicine['gst_percent'], // <--- Added this
                'expiry_date' => $medicine['expiry_date'],
            ];
        }

        // Using syncWithoutDetaching to add new or update existing.
        // It won't remove any medicines not in $syncData.
        $batch->medicines()->syncWithoutDetaching($syncData);

        // If you need to explicitly update quantity/price/ptr/expiry for items already attached
        // and also ensure new ones are added, syncWithoutDetaching is generally sufficient.
        // If you want to update *only* existing ones without adding new,
        // you'd typically iterate through $syncData and use updateExistingPivot
        // for each, but syncWithoutDetaching handles both.
        // For distinct update logic, see updateMedicinesInBatch.

        return $batch;
    }

    public function updateMedicinesInBatch($batchId, array $medicines)
    {
        $batch = Batch::findOrFail($batchId);

        foreach ($medicines as $medicine) {
            // It's important to pass all pivot fields that need updating,
            // including expiry_date if it can be updated after initial creation.
            $batch->medicines()->updateExistingPivot($medicine['medicine_id'], [
                'quantity' => $medicine['quantity'],
                'price' => $medicine['price'],
                'ptr' => $medicine['ptr'],
                'gst_percent' => $medicine['gst_percent'], // <--- Added this
                'expiry_date' => $medicine['expiry_date'], // <--- Added expiry_date here too for update
            ]);
        }

        return $batch;
    }

    public function removeMedicineFromBatch($batchId, $medicineId)
    {
        $batch = Batch::findOrFail($batchId);
        return $batch->medicines()->detach($medicineId);
    }
}