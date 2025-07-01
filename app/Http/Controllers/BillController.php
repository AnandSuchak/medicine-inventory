<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\CompanyDetail;
use App\Models\Customer;
use App\Models\Medicine;
use App\Models\MedicineBatch;
use App\Repositories\Interfaces\BillRepositoryInterface;
use Illuminate\Http\Request;

class BillController extends Controller
{
    protected $billRepository;

    public function __construct(BillRepositoryInterface $billRepository)
    {
        $this->billRepository = $billRepository;
    }

    public function index()
    {
        $bills = $this->billRepository->all();
        return view('bills.index', compact('bills'));
    }

    public function create()
    {
        // We no longer need to pass all customers/medicines, we will use search
        return view('bills.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'medicines' => 'required|array|min:1',
            'medicines.*.id' => 'required|exists:medicines,id',
            'medicines.*.quantity' => 'required|integer|min:1',
            'medicines.*.price' => 'required|numeric|min:0',
            'medicines.*.discount' => 'nullable|numeric|min:0|max:100',
        ]);

        try {
            $this->billRepository->create($request->all());
            return redirect()->route('bills.index')->with('success', 'Bill created successfully.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function show($id)
    {
        $bill = $this->billRepository->find($id);
        // Get company details to display on the invoice
        $companyDetails = CompanyDetail::first();
        return view('bills.show', compact('bill', 'companyDetails'));
    }

    public function edit($id)
    {
        // As implemented in the repository, edit is complex.
        // For now, we will redirect to the show page with a message.
        return redirect()->route('bills.show', $id)->with('info', 'Editing bills is not currently supported. Please delete and recreate if changes are needed.');
    }

    public function update(Request $request, $id)
    {
        // Not implemented
        abort(404);
    }

        public function destroy(Bill $bill)
        {
            try {
                $this->billRepository->delete($bill->id);
                // Optional: Add a success message to the session
                // return redirect()->route('bills.index')->with('success', 'Bill deleted successfully.');
            } catch (\Exception $e) {
                // Optional: Log the error and show an error message
                // return redirect()->route('bills.index')->with('error', 'Failed to delete bill.');
            }

            return redirect()->route('bills.index');
        }
            
    // This search is for the medicine dropdown on the sales form.
    // It now includes the total available quantity.
    public function searchMedicines(Request $request)
    {
        $search = $request->get('term');
        $medicines = Medicine::where('name', 'LIKE', "%{$search}%")->get();

        $results = [];
        foreach($medicines as $medicine) {
            $totalStock = MedicineBatch::where('medicine_id', $medicine->id)->sum('quantity');
            if ($totalStock > 0) {
                 $results[] = [
                    'id' => $medicine->id,
                    'text' => $medicine->name . " (Stock: {$totalStock})",
                    'gst' => $medicine->gst,
                 ];
            }
        }

        return response()->json(['results' => $results]);
    }

    // This search is for the customer dropdown on the sales form.
    public function searchCustomers(Request $request)
    {
        $search = $request->get('term');
        $customers = Customer::where('shop_name', 'LIKE', "%{$search}%")->get();
        return response()->json($customers->map(function($customer) {
            return [
                'id' => $customer->id,
                'text' => $customer->shop_name . ' (' . $customer->phone . ')'
            ];
        }));
    }
}