<?php

namespace App\Http\Controllers;

use App\Models\PurchaseBill;
use App\Repositories\Interfaces\MedicineRepositoryInterface;
use App\Repositories\Interfaces\PurchaseBillRepositoryInterface;
use App\Repositories\Interfaces\SupplierRepositoryInterface;
use Illuminate\Http\Request;

class PurchaseBillController extends Controller
{
    protected $purchaseBillRepository;
    protected $supplierRepository;
    protected $medicineRepository;

    public function __construct(
        PurchaseBillRepositoryInterface $purchaseBillRepository,
        SupplierRepositoryInterface $supplierRepository,
        MedicineRepositoryInterface $medicineRepository
    ) {
        $this->purchaseBillRepository = $purchaseBillRepository;
        $this->supplierRepository = $supplierRepository;
        $this->medicineRepository = $medicineRepository;
    }

    public function index()
    {
        $purchaseBills = $this->purchaseBillRepository->all();
        return view('purchase_bills.index', compact('purchaseBills'));
    }

    public function create()
    {
        $suppliers = $this->supplierRepository->all();
        $purchaseBillNumber = 'PB-' . date('Ymd') . '-' . (PurchaseBill::count() + 1);
        return view('purchase_bills.create', compact('suppliers', 'purchaseBillNumber'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'batch_number' => 'required|string|unique:purchase_bills,batch_number',
            'purchase_date' => 'required|date',
            'supplier_id' => 'required|exists:suppliers,id',
            'supplier_invoice_no' => 'nullable|string|max:255',
            'cash_discount_percentage' => 'nullable|numeric|min:0|max:100',
            'medicines' => 'required|array|min:1',
            'medicines.*.id' => 'required|exists:medicines,id',
            'medicines.*.batch_no' => 'required|string|max:255', // New validation
            'medicines.*.quantity' => 'required|integer|min:1',
            'medicines.*.price' => 'required|numeric|min:0',
            'medicines.*.expiry_date' => 'required|date',
            'medicines.*.mrp' => 'required|numeric|min:0',
            'medicines.*.ptr' => 'nullable|numeric|min:0',
            'medicines.*.discount_percentage' => 'nullable|numeric|min:0|max:100',
        ]);

        $this->purchaseBillRepository->create($request->all());

        return redirect()->route('purchase_bills.index')->with('success', 'Purchase Bill created successfully.');
    }

    public function show($id)
    {
        $purchaseBill = $this->purchaseBillRepository->find($id);
        return view('purchase_bills.show', compact('purchaseBill'));
    }

    public function edit($id)
    {
        $purchaseBill = $this->purchaseBillRepository->find($id);
        $suppliers = $this->supplierRepository->all();
        return view('purchase_bills.edit', compact('purchaseBill', 'suppliers'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'purchase_date' => 'required|date',
            'supplier_id' => 'required|exists:suppliers,id',
            'supplier_invoice_no' => 'nullable|string|max:255',
            'cash_discount_percentage' => 'nullable|numeric|min:0|max:100',
            'medicines' => 'required|array|min:1',
            'medicines.*.id' => 'required|exists:medicines,id',
            'medicines.*.batch_no' => 'required|string|max:255', // New validation
            'medicines.*.quantity' => 'required|integer|min:1',
            'medicines.*.price' => 'required|numeric|min:0',
            'medicines.*.expiry_date' => 'required|date',
            'medicines.*.mrp' => 'required|numeric|min:0',
            'medicines.*.ptr' => 'nullable|numeric|min:0',
            'medicines.*.discount_percentage' => 'nullable|numeric|min:0|max:100',
        ]);

        $this->purchaseBillRepository->update($id, $request->all());

        return redirect()->route('purchase_bills.index')->with('success', 'Purchase Bill updated successfully.');
    }

    public function destroy($id)
    {
        $this->purchaseBillRepository->delete($id);
        return redirect()->route('purchase_bills.index')->with('success', 'Purchase Bill deleted successfully.');
    }

    public function searchMedicines(Request $request)
    {
        $search = $request->get('term');
        $medicines = $this->medicineRepository->search($search);

        $results = [];
        foreach ($medicines as $medicine) {
            $results[] = [
                'id' => $medicine->id,
                'text' => $medicine->name . ' (' . $medicine->mfg_company_name . ')',
                'gst' => $medicine->gst,
            ];
        }

        return response()->json(['results' => $results]);
    }
}