<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use Illuminate\Http\Request;

class MedicineController extends Controller
{
    // Show the list of medicines
    public function index()
    {
        $medicines = Medicine::all(); // Get all medicines
        return view('medicines.index', compact('medicines'));
    }

    // Show the form for creating a new medicine
    public function create()
    {
        return view('medicines.create');
    }

    // Store a newly created medicine
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'hsn_code' => 'required|string|max:20',
            'description' => 'nullable|string',
            'quantity' => 'required|integer',
        ]);
    
        Medicine::create($request->only(['name', 'hsn_code', 'description', 'price', 'quantity']));
    
        return redirect()->route('medicines.index')->with('success', 'Medicine added successfully!');
    }
    

    // Show the details of a medicine
    public function show($id)
    {
        $medicine = Medicine::findOrFail($id); // Handle case if not found
        return view('medicines.show', compact('medicine'));
    }

    // Show the form for editing an existing medicine
    public function edit($id)
    {
        $medicine = Medicine::findOrFail($id); // Handle case if not found
        return view('medicines.edit', compact('medicine'));
    }

    // Update an existing medicine
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'hsn_code' => 'required|string|max:20',
            'description' => 'nullable|string',
            'quantity' => 'required|integer',
        ]);
    
        $medicine = Medicine::findOrFail($id);
        $medicine->update($request->only(['name', 'hsn_code', 'description', 'price', 'quantity']));
    
        return redirect()->route('medicines.index')->with('success', 'Medicine updated successfully!');
    }

    // Remove a medicine
    public function destroy($id)
    {
        try {
            $medicine = Medicine::findOrFail($id);
            $medicine->delete();

            return redirect()->route('medicines.index')->with('success', 'Medicine deleted successfully!');
        } catch (\Exception $e) {
            // Handle failure (e.g., item not found or database issues)
            return redirect()->route('medicines.index')->with('error', 'Failed to delete medicine!');
        }
    }
}
