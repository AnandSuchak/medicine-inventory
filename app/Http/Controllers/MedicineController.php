<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Interfaces\MedicineRepositoryInterface;

class MedicineController extends Controller
{
    protected $medicineRepo;

    public function __construct(MedicineRepositoryInterface $medicineRepo)
    {
        $this->medicineRepo = $medicineRepo;
    }

    public function index()
    {
        $medicines = $this->medicineRepo->allPaginated(10);
        return view('medicines.index', compact('medicines'));
    }

    public function create()
    {
        return view('medicines.create');
    }

    public function store(Request $request)
    {
        // Updated validation to match the new table schema
        $request->validate([
            'name' => 'required|string|max:255',
            'hsn_code' => 'required|string|max:20',
            'description' => 'nullable|string',
            'unit' => 'required|string|max:50', // Added unit validation
        ]);

        // Only pass fields that are in the medicines table
        $this->medicineRepo->create($request->only(['name', 'hsn_code', 'description', 'unit']));

        return redirect()->route('medicines.index')->with('success', 'Medicine added successfully!');
    }

    public function show($id)
    {
        $medicine = $this->medicineRepo->find($id);
        return view('medicines.show', compact('medicine'));
    }

    public function edit($id)
    {
        $medicine = $this->medicineRepo->find($id);
        return view('medicines.edit', compact('medicine'));
    }

    public function update(Request $request, $id)
    {
        // Updated validation to match the new table schema
        $request->validate([
            'name' => 'required|string|max:255',
            'hsn_code' => 'required|string|max:20',
            'description' => 'nullable|string',
            'unit' => 'required|string|max:50', // Added unit validation
        ]);

        // Only pass fields that are in the medicines table
        $this->medicineRepo->update($id, $request->only(['name', 'hsn_code', 'description', 'unit']));

        return redirect()->route('medicines.index')->with('success', 'Medicine updated successfully!');
    }

    public function destroy($id)
    {
        try {
            $this->medicineRepo->delete($id);
            return redirect()->route('medicines.index')->with('success', 'Medicine deleted successfully!');
        } catch (\Exception $e) {
            \Log::error("Failed to delete medicine ID {$id}: " . $e->getMessage());
            return redirect()->route('medicines.index')->with('error', 'Failed to delete medicine. It might be associated with other records!');
        }
    }
}