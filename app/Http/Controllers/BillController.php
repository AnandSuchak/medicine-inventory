<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\Medicine;
use App\Models\Batch;
use App\Models\Customer;
use App\Models\BatchMedicine; // Assuming you have this model for pivot
use Illuminate\Http\Request;

class BillController extends Controller
{
    // Show all bills with their customers, newest first
    public function index()
    {
        $bills = Bill::with('customer')->latest()->get();
        return view('bills.index', compact('bills'));
    }

    // Show form to create a new bill
public function create()
{
    $customers = Customer::all();
    $medicines = Medicine::all();

    $batches = Batch::with(['medicines' => function ($query) {
        $query->orderBy('batch_medicine.expiry_date', 'asc');
    }])->get();

    // Group batches by medicine ID (for easy access in JS)
    $batchesByMedicine = [];
foreach ($batches as $batch) {
    foreach ($batch->medicines as $medicine) {
        $batchesByMedicine[$medicine->id][] = [
            'batch_id' => $batch->id,
            'batch_code' => $batch->batch_code, // <-- ensure this line is present
            'expiry_date' => $medicine->pivot->expiry_date,
            'quantity' => $medicine->pivot->quantity,
            'price' => $medicine->pivot->price,
            'ptr' => $medicine->pivot->ptr,
            'gst_percent' => $medicine->pivot->gst_percent,
        ];
    }
}



    $stock = collect($batchesByMedicine)->map(function ($batchList) {
        return collect($batchList)->sum('quantity');
    });

    return view('bills.create', compact(
        'customers',
        'medicines',
        'stock',
        'batchesByMedicine'
    ));
}
public function store(Request $request)
{
    $request->validate([
        'medicines.*.medicine_id' => 'required|exists:medicines,id',
        'medicines.*.quantity' => 'required|integer|min:1',
        'medicines.*.price' => 'required|numeric|min:0',
        'medicines.*.ptr' => 'required|numeric|min:0',
    ]);

    $bill = Bill::create([
        'customer_id' => $request->customer_id, // Add if needed
        'status' => 'Ordered',
        'total_amount' => 0, // Will be updated later
    ]);

    $totalAmount = 0;

    foreach ($request->medicines as $medicineData) {
        $medicineId = $medicineData['medicine_id'];
        $quantityNeeded = $medicineData['quantity'];
        $ptr = $medicineData['ptr'];
        $price = $medicineData['price'];

        $batches = BatchMedicine::where('medicine_id', $medicineId)
            ->where('quantity', '>', 0)
            ->orderBy('expiry_date')
            ->get();

        foreach ($batches as $batch) {
            if ($quantityNeeded <= 0) break;

            $availableQty = $batch->quantity;

            $usedQty = min($quantityNeeded, $availableQty);

            // Reduce stock
            $batch->quantity -= $usedQty;
            $batch->save();

            // Save this batch allocation for the bill
            $bill->medicines()->attach($medicineId, [
                'batch_id' => $batch->batch_id,
                'quantity' => $usedQty,
                'expiry_date' => $batch->expiry_date,
                'price' => $price,
                'ptr' => $ptr,
            ]);

            $subtotal = $ptr * $usedQty;
            $gst = $subtotal * ($batch->gst_percent / 100);
            $total = $subtotal + $gst;

            $totalAmount += $total;

            $quantityNeeded -= $usedQty;
        }

        if ($quantityNeeded > 0) {
            return back()->withErrors(['stock' => 'Insufficient stock for medicine ID ' . $medicineId]);
        }
    }

    $bill->total_amount = $totalAmount;
    $bill->save();

    return redirect()->route('bills.index')->with('success', 'Bill generated successfully!');
}


    // Show single bill with its medicines, batches and customer
    public function show($id)
    {
        $bill = Bill::with('medicines.batch', 'customer')->findOrFail($id);
        return view('bills.show', compact('bill'));
    }
}
