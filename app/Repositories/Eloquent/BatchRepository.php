<?php

namespace App\Repositories\Eloquent;

use App\Models\Batch;
use App\Repositories\Interfaces\BatchRepositoryInterface;

class BatchRepository implements BatchRepositoryInterface
{
    public function allWithSupplierAndMedicineCount()
    {
        // Use paginate for better performance, especially if many batches
        return Batch::with('supplier')->withCount('medicines')->latest()->paginate(10); // Added pagination
    }

    public function create(array $data)
    {
        return Batch::create($data);
    }

    public function find($id)
    {
        // === THE FIX IS HERE: Changed 'medicines.medicine' to just 'medicines' ===
        return Batch::with(['supplier', 'medicines'])->findOrFail($id); // Correctly eager load medicines
    }

    public function update($id, array $data)
    {
        $batch = Batch::findOrFail($id);
        $batch->update($data);
        return $batch;
    }

    public function delete($batch)
    {
        // Ensure $batch is an Eloquent model instance
        if ($batch instanceof Batch) {
            return $batch->delete();
        }
        // If an ID was passed instead of a model, find and delete
        return Batch::destroy($batch);
    }
}