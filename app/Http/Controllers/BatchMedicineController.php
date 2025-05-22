<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Batch;
use App\Models\Medicine;

class BatchMedicineController extends Controller
{
    // Show form to add medicines to a specific batch
    public function create($batchId)
    {
        $batch = Batch::findOrFail($batchId);
        $medicines = Medicine::all(); // List of all medicines to add to batch

        return view('batch_medicines.create', compact('batch', 'medicines'));
    }

    // Store medicines attached to a batch
    public function store(Request $request, $batchId)
    {
        $batch = Batch::findOrFail($batchId);

        $request->validate([
            'medicines' => 'required|array|min:1',
            'medicines.*.medicine_id' => 'required|exists:medicines,id',
            'medicines.*.quantity' => 'required|integer|min:1',
            'medicines.*.price' => 'required|numeric|min:0',
            'medicines.*.ptr' => 'required|numeric|min:0',
            'medicines.*.expiry_date' => 'required|date',
        ]);

        foreach ($request->medicines as $medicineData) {
            // Attach or update pivot for each medicine in batch
            $batch->medicines()->syncWithoutDetaching([
                $medicineData['medicine_id'] => [
                    'quantity' => $medicineData['quantity'],
                    'price' => $medicineData['price'],
                    'ptr' => $medicineData['ptr'],
                    'expiry_date' => $medicineData['expiry_date'],
                ]
            ]);
        }

        return redirect()->route('batches.show', $batchId)
                         ->with('success', 'Medicines added/updated successfully!');
    }

    // Show form to edit medicines in a batch
    public function edit($batchId)
    {
        $batch = Batch::with('medicines')->findOrFail($batchId);
        $medicines = $batch->medicines;

        return view('batch_medicines.edit', compact('batch', 'medicines'));
    }

    // Update medicines in batch
    public function update(Request $request, $batchId)
    {
        $batch = Batch::findOrFail($batchId);

        $request->validate([
            'medicines' => 'required|array|min:1',
            'medicines.*.medicine_id' => 'required|exists:medicines,id',
            'medicines.*.quantity' => 'required|integer|min:0',
            'medicines.*.price' => 'required|numeric|min:0',
            'medicines.*.ptr' => 'required|numeric|min:0',
        ]);

        foreach ($request->medicines as $medicineData) {
            $batch->medicines()->updateExistingPivot($medicineData['medicine_id'], [
                'quantity' => $medicineData['quantity'],
                'price' => $medicineData['price'],
                'ptr' => $medicineData['ptr'],
            ]);
        }

        return redirect()->route('batches.show', $batchId)->with('success', 'Batch medicines updated successfully.');
    }

    // Remove medicine from batch
    public function remove($batchId, $medicineId)
    {
        $batch = Batch::findOrFail($batchId);
        $batch->medicines()->detach($medicineId);

        return redirect()->route('batch_medicines.edit', $batchId)->with('success', 'Medicine removed successfully!');
    }
}
