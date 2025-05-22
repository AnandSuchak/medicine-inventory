<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    // Show a list of all customers (with pagination)
    public function index()
    {
        $customers = Customer::paginate(10); // Paginate customers
        return view('customers.index', compact('customers'));
    }

    // Show the form for creating a new customer
    public function create()
    {
        return view('customers.create');
    }

    // Store a newly created customer in the database
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:255',
        ]);

        Customer::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'address' => $request->address,
        ]);

        return redirect()->route('customers.index')->with('success', 'Customer created successfully!');
    }

    // Show the form for editing a customer
    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    // Update the specified customer in the database
    public function update(Request $request, Customer $customer)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20', // You can adjust max length based on phone format
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:255',
        ]);

        $customer->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'address' => $request->address,
        ]);

        return redirect()->route('customers.index')->with('success', 'Customer updated successfully!');
    }

    // Remove the specified customer from the database
    public function destroy(Customer $customer)
    {
        $customer->delete();
        return redirect()->route('customers.index')->with('success', 'Customer deleted successfully!');
    }
}
