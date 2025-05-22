<?php
namespace App\Http\Controllers;

use App\Models\Medicine;
use Illuminate\Http\Request;

class StockController extends Controller
{
    // Show list of medicines with total stock calculated from batch_medicine pivot
    public function index()
    {
        $medicines = Medicine::with('batches')->get();

        return view('stocks.index', compact('medicines'));
    }

    // Show detailed stock breakdown for a medicine by fetching pivot data from batches
    public function show($medicine_id)
    {
        $medicine = Medicine::findOrFail($medicine_id);

        // Load batches with pivot data for quantity, price, ptr, expiry_date
        $batches = $medicine->batches()->withPivot('quantity', 'price', 'ptr', 'expiry_date')->with('supplier')->get();

        return view('stocks.show', compact('medicine', 'batches'));
    }
}
