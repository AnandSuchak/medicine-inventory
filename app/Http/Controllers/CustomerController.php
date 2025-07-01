<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Repositories\Interfaces\CustomerRepositoryInterface;

class CustomerController extends Controller
{
    protected $customerRepository;

    public function __construct(CustomerRepositoryInterface $customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }

    public function index()
    {
        $customers = $this->customerRepository->allPaginated(10);
        return view('customers.index', compact('customers'));
    }

    public function create()
    {
        return view('customers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:255',
        ]);

        $this->customerRepository->create($request->only(['name', 'phone', 'email', 'address']));

        return redirect()->route('customers.index')->with('success', 'Customer created successfully!');
    }

    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:255',
        ]);

        $this->customerRepository->update($customer, $request->only(['name', 'phone', 'email', 'address']));

        return redirect()->route('customers.index')->with('success', 'Customer updated successfully!');
    }

        public function show($id) // Changed from Customer $customer to $id
    {
        // Use your repository to find the customer by ID
        $customer = $this->customerRepository->findById($id);

        // If for some reason findById returns null, you might want to handle it (e.g., abort(404))
        if (!$customer) {
            abort(404, 'Customer not found.');
        }

        return view('customers.show', compact('customer'));
    }

    
    public function destroy(Customer $customer)
    {
        $this->customerRepository->delete($customer);

        return redirect()->route('customers.index')->with('success', 'Customer deleted successfully!');
    }
}
