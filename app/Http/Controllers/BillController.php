<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\Customer;
use App\Models\Medicine;
use App\Models\BatchMedicine; // Make sure BatchMedicine is imported!
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Repositories\Interfaces\BillRepositoryInterface; // Import the interface
use Exception; // Make sure Exception is imported for error handling

class BillController extends Controller
{
    /**
     * @var BillRepositoryInterface
     */
    protected $billRepository; // Declare the property

    /**
     * BillController constructor.
     * Inject the BillRepositoryInterface through the constructor.
     *
     * @param BillRepositoryInterface $billRepository
     */
    public function __construct(BillRepositoryInterface $billRepository)
    {
        $this->billRepository = $billRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bills = Bill::orderBy('created_at', 'desc')->paginate(10);
        return view('bills.index', compact('bills'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $customers = Customer::all();
        return view('bills.create', compact('customers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'bill_date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.medicine_id' => 'required|exists:medicines,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0', // Submitted but re-verified from batch
            'items.*.gst_rate_percentage' => 'required|numeric|min:0', // Submitted but re-verified from batch
            'discount_amount' => 'nullable|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $billNumber = 'BILL-' . now()->format('YmdHis') . '-' . strtoupper(Str::random(4));

            $bill = Bill::create([
                'customer_id' => $request->customer_id,
                'bill_date' => $request->bill_date,
                'notes' => $request->notes,
                'discount_amount' => $request->discount_amount ?? 0,
                'sub_total' => 0,
                'total_gst_amount' => 0,
                'net_amount' => 0,
                'bill_number' => $billNumber,
                'sub_total_before_tax' => 0,
                'payment_status' => 'pending',
                'status' => 'completed',
            ]);

            foreach ($request->items as $itemData) {
                $medicine = Medicine::find($itemData['medicine_id']);
                if (!$medicine) {
                    throw ValidationException::withMessages(['items' => 'Medicine not found.']);
                }

                $requestedQuantity = (int) $itemData['quantity'];
                $remainingQuantityToFulfill = $requestedQuantity;

                $overallAvailableStock = $medicine->batchMedicines->sum('quantity');
                if ($requestedQuantity > $overallAvailableStock) {
                    throw ValidationException::withMessages([
                        'items' => 'Insufficient stock for ' . $medicine->name . '. Only ' . $overallAvailableStock . ' available.'
                    ]);
                }

                $batches = $medicine->batchMedicines()
                                    ->where('quantity', '>', 0)
                                    ->orderBy('expiry_date', 'asc')
                                    ->get();

                foreach ($batches as $batchMedicine) {
                    if ($remainingQuantityToFulfill <= 0) break;

                    $quantityFromThisBatch = min($remainingQuantityToFulfill, $batchMedicine->quantity);

                    $unitPriceForThisBatch = (float) $batchMedicine->price; // Use batch-specific price
                    $gstRatePercentageForThisBatch = (float) $batchMedicine->gst_percent; // Use batch-specific GST

                    $itemSubTotalFromBatch = $quantityFromThisBatch * $unitPriceForThisBatch;
                    $itemGstAmountFromBatch = ($itemSubTotalFromBatch * $gstRatePercentageForThisBatch) / 100;
                    $totalAmountAfterTaxFromBatch = $itemSubTotalFromBatch + $itemGstAmountFromBatch;

                    // Create a BillItem record for THIS BATCH's contribution
                    // Use the existing 'batch_id' column
                    $bill->billItems()->create([
                        'medicine_id' => $medicine->id,
                        'batch_id' => $batchMedicine->id, // Store the batch ID in the existing column!
                        'quantity' => $quantityFromThisBatch,
                        'unit_price' => $unitPriceForThisBatch,
                        'gst_rate_percentage' => $gstRatePercentageForThisBatch,
                        'sub_total' => $itemSubTotalFromBatch,
                        'item_gst_amount' => $itemGstAmountFromBatch,
                        'total_amount_after_tax' => $totalAmountAfterTaxFromBatch,
                    ]);

                    $batchMedicine->quantity -= $quantityFromThisBatch;
                    $batchMedicine->save();

                    $remainingQuantityToFulfill -= $quantityFromThisBatch;
                }
            }

            $bill->load('billItems');
            $totalBillSubTotal = $bill->billItems->sum('sub_total');
            $totalBillGstAmount = $bill->billItems->sum('item_gst_amount');
            $netAmount = ($totalBillSubTotal + $totalBillGstAmount) - $bill->discount_amount;

            $bill->update([
                'sub_total' => $totalBillSubTotal,
                'total_gst_amount' => $totalBillGstAmount,
                'net_amount' => $netAmount,
                'sub_total_before_tax' => $totalBillSubTotal,
            ]);

            DB::commit();
            return redirect()->route('bills.show', $bill->id)->with('success', 'Bill generated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            // Log the error for debugging
         
            return redirect()->back()->withInput()->with('error', 'Error generating bill: ' . $e->getMessage());
        }
    }

    public function show(Bill $bill)
    {
        // Eager load the customer and items relationships for the bill
        $bill->load('customer', 'billItems.medicine'); 
        return view('bills.show', compact('bill'));
    }

    /**
     * API endpoint to search for medicines for Select2.
     * Returns results in {id: value, text: 'Medicine Name (Unit)'} format.
     */
    public function searchMedicines(Request $request)
    {
        $search = $request->query('q');
        $medicines = Medicine::select('id', 'name', 'unit')
                             ->where('name', 'LIKE', '%' . $search . '%')
                             ->limit(10)
                             ->get();

        $formattedMedicines = $medicines->map(function ($medicine) {
            return [
                'id' => $medicine->id,
                'text' => $medicine->name . ' (' . $medicine->unit . ')'
            ];
        });

        return response()->json($formattedMedicines);
    }

    /**
     * API endpoint to get detailed stock information for a medicine.
     * Fetches total available quantity from `batch_medicines.quantity`.
     * Fetches unit_price from `batch_medicines.price`.
     * Fetches gst_rate_percentage from `batch_medicines.gst_percent`.
     */
    public function getMedicineStockInfo($medicineId)
    {
        // Eager load batchMedicines that have stock, ordered by expiry_date (FIFO)
        $medicine = Medicine::with(['batchMedicines' => function($query) {
            // Use 'quantity' column for available stock check
            $query->where('quantity', '>', 0)->orderBy('expiry_date', 'asc');
        }])->find($medicineId);

        if (!$medicine) {
            return response()->json([
                'total_available_quantity' => 0,
                'unit_price' => 0.00,
                'gst_rate_percentage' => 0.00,
            ], 404);
        }

        // Sum total available quantity from all associated batch medicines
        // Use 'quantity' column for summing stock
        $totalAvailableQuantity = $medicine->batchMedicines->sum('quantity');

        $unitPrice = 0.00;
        $gstRatePercentage = 0.00;

        // Get the first available batch (earliest expiry, with stock) to determine price and GST
        $firstAvailableBatch = $medicine->batchMedicines->first();

        if ($firstAvailableBatch) {
            // Use 'price' and 'gst_percent' columns from the BatchMedicine model
            $unitPrice = (float) $firstAvailableBatch->price;
            $gstRatePercentage = (float) $firstAvailableBatch->gst_percent;
        }

        return response()->json([
            'total_available_quantity' => (int) $totalAvailableQuantity,
            'unit_price' => $unitPrice,
            'gst_rate_percentage' => $gstRatePercentage,
        ]);
    }

    /**
     * API endpoint to search for customers for Select2.
     */
    public function searchCustomers(Request $request)
    {
        $search = $request->query('q');
        $customers = Customer::select('id', 'name', 'phone')
                                 ->where('name', 'LIKE', '%' . $search . '%')
                                 ->orWhere('phone', 'LIKE', '%' . $search . '%')
                                 ->limit(10)
                                 ->get();

        $formattedCustomers = $customers->map(function ($customer) {
            return [
                'id' => $customer->id,
                'text' => $customer->name . ' (' . $customer->phone . ')'
            ];
        });

        return response()->json($formattedCustomers);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(int $id)
    {
        $bill = $this->billRepository->find($id); // Use the repository to find the bill
        if (!$bill) {
            abort(404); // Or redirect with an error
        }

        $customers = Customer::all(); // Fetch all customers for the dropdown
        // You might need to fetch all medicines if your edit view uses a similar dynamic medicine search as create
        // $medicines = Medicine::all(); // If you need all medicines upfront, otherwise use AJAX

        return view('bills.edit', compact('bill', 'customers'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id)
    {
        // 1. Validation
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'bill_date' => 'required|date',
            'notes' => 'nullable|string|max:1000',
            'discount_amount' => 'nullable|numeric|min:0',
            'status' => 'required|string|in:pending,paid,cancelled', // Add if bill status can be edited
            'items' => 'required|array|min:1',
            'items.*.id' => 'nullable|exists:bill_items,id', // For existing items
            'items.*.medicine_id' => 'required|exists:medicines,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.gst_rate_percentage' => 'required|numeric|min:0',
            // Ensure you validate calculated fields if they are submitted, or remove from validation if re-calculated
            'items.*.item_gst_amount' => 'required|numeric|min:0',
            'items.*.sub_total' => 'required|numeric|min:0',
            'items.*.total_amount_after_tax' => 'required|numeric|min:0',
            'deleted_item_ids' => 'nullable|array', // Array of item IDs to be deleted
            'deleted_item_ids.*' => 'exists:bill_items,id',
        ]);

        try {
            $billDetails = $request->only([
                'customer_id', 'bill_date', 'notes', 'discount_amount', 'status'
            ]);
            $items = $request->input('items', []);
            $deletedItemIds = $request->input('deleted_item_ids', []);

            $this->billRepository->updateBill($id, $billDetails, $items, $deletedItemIds);

            return redirect()->route('bills.show', $id)->with('success', 'Bill updated successfully!');

        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (Exception $e) { // Use the imported Exception class
            // Log the error for debugging
           
            return back()->with('error', 'Error updating bill: ' . $e->getMessage())->withInput();
        }
    }
}
