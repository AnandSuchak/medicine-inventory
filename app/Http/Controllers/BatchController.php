<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Interfaces\BatchRepositoryInterface;
use App\Repositories\Interfaces\SupplierRepositoryInterface; // Import SupplierRepositoryInterface
use Illuminate\Validation\Rule; // Import Rule for validation
use App\Models\Supplier;
use App\Models\Batch;

class BatchController extends Controller
{
    protected $batchRepo;
    protected $supplierRepo; // Declare supplier repository

    public function __construct(
        BatchRepositoryInterface $batchRepo,
        SupplierRepositoryInterface $supplierRepo // Inject SupplierRepository
    ) {
        $this->batchRepo = $batchRepo;
        $this->supplierRepo = $supplierRepo;
    }

    public function index()
    {
        // Using the allWithSupplierAndMedicineCount method from the repository
        $batches = $this->batchRepo->allWithSupplierAndMedicineCount();
        return view('batches.index', compact('batches'));
    }

    public function create()
    {
        $suppliers = $this->supplierRepo->all(); // Fetch all suppliers for the dropdown
        return view('batches.create', compact('suppliers'));
    }

    public function store(Request $request)
    {
        // Validate inputs excluding batch_number as it's auto-generated
        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'purchase_date' => 'required|date',
            'status' => 'required|in:active,inactive,completed',
        ]);

        // --- Batch Number Generation Logic (copied from your snippet) ---
        $supplier = Supplier::findOrFail($validated['supplier_id']);
        $supplierPrefix = strtoupper(substr($supplier->name, 0, 2));
        $date = now()->format('d'); // Use Carbon's now() for current date
        $month = now()->format('m'); // Use Carbon's now() for current month
        $batchPrefix = $supplierPrefix . $date . $month;

        // Count batches created today to get the sequential number
        // Ensure you count only batches created on the same day for the sequential part
        $batchCount = Batch::whereDate('created_at', now()->toDateString())->count();
        $batchNumber = $batchPrefix . str_pad($batchCount + 1, 2, '0', STR_PAD_LEFT);
        // --- End Batch Number Generation Logic ---

        $batch = Batch::create([
            'batch_number' => $batchNumber, // Assign the generated batch number
            'supplier_id' => $validated['supplier_id'],
            'purchase_date' => $validated['purchase_date'],
            'status' => $validated['status'],
        ]);

        return redirect()->route('batches.show', $batch->id)
                         ->with('success', 'Batch created successfully with number: ' . $batch->batch_number);
    }

    public function show($id)
    {
        // The find method in BatchRepository eager loads supplier and medicines
        $batch = $this->batchRepo->find($id);
        return view('batches.show', compact('batch'));
    }

    public function edit($id)
    {
        $batch = $this->batchRepo->find($id);
        $suppliers = $this->supplierRepo->all(); // Fetch all suppliers for the dropdown
        return view('batches.edit', compact('batch', 'suppliers'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'batch_number' => 'required|string|max:255|unique:batches,batch_number,' . $id,
            'supplier_id' => 'required|exists:suppliers,id',
            'status' => ['required', Rule::in(['active', 'inactive', 'completed'])],
            'purchase_date' => 'required|date|before_or_equal:today',
        ]);

        $this->batchRepo->update($id, $request->only([
            'batch_number',
            'supplier_id',
            'status',
            'purchase_date',
        ]));

        return redirect()->route('batches.index')->with('success', 'Batch updated successfully!');
    }

    public function destroy($id)
    {
        $batch = $this->batchRepo->find($id); // Find the batch before deleting
        try {
            $this->batchRepo->delete($batch);
            return redirect()->route('batches.index')->with('success', 'Batch deleted successfully!');
        } catch (\Exception $e) {
        
            return redirect()->route('batches.index')->with('error', 'Failed to delete batch. It might be associated with other records!');
        }
    }
}