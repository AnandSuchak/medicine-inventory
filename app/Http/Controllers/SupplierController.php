<?php
namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    // Show all suppliers
    public function index()
    {
        $suppliers = Supplier::all();
        return view('suppliers.index', compact('suppliers'));
    }

    // Show form to create a new supplier
    public function create()
    {
        return view('suppliers.create');
    }

    // Store new supplier
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'contact_number' => 'required|string',
            'email' => 'required|email',
            'address' => 'nullable|string',
        ]);
    
        Supplier::create([
            'name' => $request->name,
            'contact_number' => $request->contact_number,
            'email' => $request->email,
            'address' => $request->address,
            'status' => $request->status, // This will be either 'active' or 'inactive'
        ]);
    
        return redirect()->route('suppliers.index');
    }
    

    // Show the form for editing the supplier
    public function edit(Supplier $supplier)
    {
        return view('suppliers.edit', compact('supplier'));
    }

    // Update the supplier in the database
    public function update(Request $request, Supplier $supplier)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'contact_number' => 'nullable|string|max:15',
            'email' => 'nullable|email',
            'address' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        $supplier->update($request->all());
        return redirect()->route('suppliers.index')->with('success', 'Supplier updated successfully.');
    }

    // Delete a supplier
    public function destroy(Supplier $supplier)
    {
        $supplier->delete();
        return redirect()->route('suppliers.index')->with('success', 'Supplier deleted successfully.');
    }
}
