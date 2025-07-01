<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Repositories\Interfaces\CustomerRepositoryInterface;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    protected $customerRepository;

    public function __construct(CustomerRepositoryInterface $customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }

    public function index()
    {
        $customers = $this->customerRepository->allPaginated();
        return view('customers.index', compact('customers'));
    }

    public function create()
    {
        return view('customers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'shop_name' => 'required|string|max:255', // Updated
            'phone' => 'required|string|max:15',
            'email' => 'nullable|email|max:255|unique:customers,email',
            'address' => 'nullable|string',
            'gst' => 'nullable|string|max:15|required_without:pan', // Updated
            'pan' => 'nullable|string|max:10|required_without:gst', // Updated
        ]);

        $this->customerRepository->create($request->all());

        return redirect()->route('customers.index')->with('success', 'Customer created successfully.');
    }

    public function show(Customer $customer)
    {
        return view('customers.show', compact('customer'));
    }

    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $request->validate([
            'shop_name' => 'required|string|max:255', // Updated
            'phone' => 'required|string|max:15',
            'email' => 'nullable|email|max:255|unique:customers,email,' . $customer->id,
            'address' => 'nullable|string',
            'gst' => 'nullable|string|max:15|required_without:pan', // Updated
            'pan' => 'nullable|string|max:10|required_without:gst', // Updated
        ]);

        $this->customerRepository->update($customer->id, $request->all());

        return redirect()->route('customers.index')->with('success', 'Customer updated successfully.');
    }

    public function destroy(Customer $customer)
    {
        $this->customerRepository->delete($customer->id);
        return redirect()->route('customers.index')->with('success', 'Customer deleted successfully.');
    }
}