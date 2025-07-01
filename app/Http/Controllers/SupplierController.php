<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Interfaces\SupplierRepositoryInterface; // Make sure this is correctly namespaced

class SupplierController extends Controller
{
    protected $supplierRepo;

    public function __construct(SupplierRepositoryInterface $supplierRepo)
    {
        $this->supplierRepo = $supplierRepo;
    }

    public function index()
    {
        $suppliers = $this->supplierRepo->allPaginated(10); // Or any number per page
        return view('suppliers.index', compact('suppliers'));
    }

    public function create()
    {
        return view('suppliers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:15',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'gstin' => 'nullable|string|max:15|unique:suppliers,gstin',
            'drug_license_id' => 'nullable|string|max:50|unique:suppliers,drug_license_id', // Added validation for new field
        ]);

        $this->supplierRepo->create([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'address' => $request->address,
            'gstin' => $request->gstin,
            'drug_license_id' => $request->drug_license_id, // Added new field to creation data
        ]);

        return redirect()->route('suppliers.index')->with('success', 'Supplier created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $supplier = $this->supplierRepo->find($id); // This uses findOrFail from your repository
        return view('suppliers.show', compact('supplier'));
    }

    public function edit($id)
    {
        $supplier = $this->supplierRepo->find($id);
        return view('suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:15',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'gstin' => 'nullable|string|max:15|unique:suppliers,gstin,' . $id,
            'drug_license_id' => 'nullable|string|max:50|unique:suppliers,drug_license_id,' . $id, // Added validation for new field
        ]);

        $this->supplierRepo->update($id, [
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'address' => $request->address,
            'gstin' => $request->gstin,
            'drug_license_id' => $request->drug_license_id, // Added new field to update data
        ]);

        return redirect()->route('suppliers.index')->with('success', 'Supplier updated successfully.');
    }

    public function destroy($id)
    {
        $this->supplierRepo->delete($id);
        return redirect()->route('suppliers.index')->with('success', 'Supplier deleted successfully.');
    }
}
