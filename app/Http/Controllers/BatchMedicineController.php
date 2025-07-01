<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Batch;
use App\Models\Medicine;
use App\Repositories\Interfaces\BatchMedicineRepositoryInterface; // Ensure this interface exists

class BatchMedicineController extends Controller
{
    protected $batchMedicineRepo;

    public function __construct(BatchMedicineRepositoryInterface $batchMedicineRepo)
    {
        $this->batchMedicineRepo = $batchMedicineRepo;
    }

    /**
     * Show the form for adding medicines to a specific batch.
     *
     * @param int $batchId
     * @return \Illuminate\View\View
     */
    public function create($batchId)
    {
        $batch = Batch::findOrFail($batchId);
        // Fetch all medicines to populate the dropdowns for adding new entries
        $medicines = Medicine::orderBy('name')->get(); 

        return view('batch_medicines.create', compact('batch', 'medicines'));
    }

    /**
     * Store newly added medicines for a specific batch.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $batchId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, $batchId)
    {
        $request->validate([
            'medicines' => 'required|array|min:1',
            'medicines.*.medicine_id' => 'required|exists:medicines,id',
            'medicines.*.quantity' => 'required|integer|min:1',
            'medicines.*.price' => 'required|numeric|min:0',
            'medicines.*.ptr' => 'required|numeric|min:0',
            'medicines.*.gst_percent' => 'required|numeric|min:0|max:100',
            'medicines.*.expiry_date' => 'required|date|after_or_equal:today',
        ]);

        $this->batchMedicineRepo->attachMedicinesToBatch($batchId, $request->medicines);

        return redirect()->route('batches.show', $batchId)
                         ->with('success', 'Medicines added/updated successfully to batch!');
    }

    /**
     * Show the form for editing medicines in a specific batch.
     *
     * @param int $batchId
     * @return \Illuminate\View\View
     */
    public function edit($batchId)
    {
        // Eager load the 'medicines' relationship on the Batch model
        // NO 'medicines.medicine' here - that would cause the error!
        $batch = Batch::with(['medicines' => function($query) {
            $query->orderBy('name'); // Optional: order medicines by name
        }])->findOrFail($batchId);

        // Fetch all medicines to populate the dropdown for adding *new* entries in the edit form
        $allMedicines = Medicine::orderBy('name')->get(); 

        return view('batch_medicines.edit', compact('batch', 'allMedicines'));
    }

    /**
     * Update existing medicines for a specific batch.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $batchId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $batchId)
    {
        $request->validate([
            'medicines' => 'required|array|min:1',
            'medicines.*.medicine_id' => 'required|exists:medicines,id',
            'medicines.*.quantity' => 'required|integer|min:0', // Can be 0 if you allow reducing to 0
            'medicines.*.price' => 'required|numeric|min:0',
            'medicines.*.ptr' => 'required|numeric|min:0',
            'medicines.*.gst_percent' => 'required|numeric|min:0|max:100',
            'medicines.*.expiry_date' => 'required|date|after_or_equal:today',
        ]);

        $this->batchMedicineRepo->updateMedicinesInBatch($batchId, $request->medicines);

        return redirect()->route('batches.show', $batchId)
                         ->with('success', 'Batch medicines updated successfully.');
    }

    /**
     * Remove a specific medicine from a batch.
     *
     * @param int $batchId
     * @param int $medicineId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function remove($batchId, $medicineId)
    {
        try {
            $this->batchMedicineRepo->removeMedicineFromBatch($batchId, $medicineId);
            return redirect()->route('batches.show', $batchId)
                             ->with('success', 'Medicine removed from batch successfully!');
        } catch (\Exception $e) {
            \Log::error("Failed to remove medicine ID {$medicineId} from batch {$batchId}: " . $e->getMessage());
            return redirect()->route('batches.show', $batchId)
                             ->with('error', 'Failed to remove medicine from batch!');
        }
    }
}