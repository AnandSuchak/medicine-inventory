<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Repositories\Interfaces\MedicineRepositoryInterface;
use Illuminate\Http\Request;

class MedicineController extends Controller
{
    protected $medicineRepository;

    public function __construct(MedicineRepositoryInterface $medicineRepository)
    {
        $this->medicineRepository = $medicineRepository;
    }

    public function index()
    {
        $medicines = $this->medicineRepository->all();
        return view('medicines.index', compact('medicines'));
    }

    public function create()
    {
        return view('medicines.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:medicines,name',
            'unit' => 'required|string|max:50',
            'description' => 'nullable|string',
            'gst' => 'required|numeric|min:0', // Added
            'pack_size' => 'required|string|max:100', // Added
            'mfg_company_name' => 'required|string|max:255', // Added
        ]);

        $this->medicineRepository->create($request->all());

        return redirect()->route('medicines.index')->with('success', 'Medicine created successfully.');
    }

    public function show(Medicine $medicine)
    {
        return view('medicines.show', compact('medicine'));
    }

    public function edit(Medicine $medicine)
    {
        return view('medicines.edit', compact('medicine'));
    }

    public function update(Request $request, Medicine $medicine)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:medicines,name,' . $medicine->id,
            'unit' => 'required|string|max:50',
            'description' => 'nullable|string',
            'gst' => 'required|numeric|min:0', // Added
            'pack_size' => 'required|string|max:100', // Added
            'mfg_company_name' => 'required|string|max:255', // Added
        ]);

        $this->medicineRepository->update($medicine->id, $request->all());

        return redirect()->route('medicines.index')->with('success', 'Medicine updated successfully.');
    }

    public function destroy(Medicine $medicine)
    {
        $this->medicineRepository->delete($medicine->id);
        return redirect()->route('medicines.index')->with('success', 'Medicine deleted successfully.');
    }
}