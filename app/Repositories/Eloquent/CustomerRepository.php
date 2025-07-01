<?php

namespace App\Repositories\Eloquent;

use App\Models\Customer;
use App\Repositories\Interfaces\CustomerRepositoryInterface;

class CustomerRepository implements CustomerRepositoryInterface
{
    public function allPaginated($perPage = 10)
    {
        return Customer::latest()->paginate($perPage);
    }

    public function create(array $data)
    {
        return Customer::create($data);
    }

    public function update($customer, array $data)
    {
        return $customer->update($data);
    }

    public function delete($customer)
    {
        return $customer->delete();
    }
        public function findById(int $id)
    {
        return Customer::find($id); // This will return the Customer model or null if not found
    }
}
