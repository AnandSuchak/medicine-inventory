<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\Supplier;
use App\Models\Medicine;
use Illuminate\Http\Request;

class BatchController extends Controller
{
    /**
     * Display a list of all batches.
     */
    public function index()
    {
        $batches = Batch::with('supplier')->withCount('medicines')->latest()->get();
        return view('batches.index', compact('batches'));
    }

    /**
     * Show the form to create a new batch.
     */
    public function create()
    {
        $suppliers = Supplier::all();
        return view('batches.create', compact('suppliers'));
    }

    /**
     * Store a newly created batch and redirect to medicine add form.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
        ]);

        $supplier = Supplier::findOrFail($validated['supplier_id']);
        $supplierPrefix = strtoupper(substr($supplier->name, 0, 2));
        $date = date('d');
        $month = date('m');
        $batchPrefix = $supplierPrefix . $date . $month;

        $batchCount = Batch::whereDate('created_at', now()->toDateString())->count();
        $batchNumber = $batchPrefix . str_pad($batchCount + 1, 2, '0', STR_PAD_LEFT);

        $batch = Batch::create([
            'batch_number' => $batchNumber,
            'supplier_id' => $supplier->id,
            'status' => 'Active',
        ]);

        return redirect()
            ->route('batches.medicines.create', ['batch' => $batch->id])
            ->with('success', 'Batch created successfully! Now add medicines to this batch.');
    }


        // BatchController.php
public function getByMedicine($medicineId)
{
    $batches = Batch::where('medicine_id', $medicineId)->get();

    return response()->json($batches);
}

    /**
     * Show a single batch with its medicines.
     */
    public function show(Batch $batch)
    {
        $batch->load(['supplier', 'medicines']);
        return view('batches.show', compact('batch'));
    }

    /**
     * Delete a batch.
     */
    public function destroy(Batch $batch)
    {
        $batch->delete();
        return back()->with('success', 'Batch deleted successfully.');
    }
}
