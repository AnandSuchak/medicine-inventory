<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Medicine; // Required for type-hinting in show method
use App\Repositories\Interfaces\StockRepositoryInterface; // Import the Stock Repository Interface

class StockController extends Controller
{
    protected $stockRepo;

    public function __construct(StockRepositoryInterface $stockRepo)
    {
        $this->stockRepo = $stockRepo;
    }

    /**
     * Display a listing of all medicines with their total stock quantity.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $stockItems = $this->stockRepo->getTotalStockItems();
        return view('stocks.index', compact('stockItems'));
    }

    /**
     * Display the specified medicine's stock details across all its batches.
     *
     * @param  Medicine $medicine (Laravel's Route Model Binding will automatically find the medicine by ID)
     * @return \Illuminate\View\View
     */
    public function show(Medicine $medicine)
    {
        $medicineDetails = $this->stockRepo->getMedicineStockDetails($medicine);

        // Calculate total stock for this specific medicine from its loaded batches
        $totalStock = $medicineDetails->batches->sum('pivot.quantity');

        return view('stocks.show', compact('medicineDetails', 'totalStock'));
    }
}