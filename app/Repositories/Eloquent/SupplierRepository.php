<?php

namespace App\Repositories\Eloquent;

use App\Models\Supplier;
use App\Repositories\Interfaces\SupplierRepositoryInterface;

class SupplierRepository implements SupplierRepositoryInterface
{
    public function all()
    {
        return Supplier::all(); // Still available if you need to fetch all without pagination
    }

    public function allPaginated($perPage = 10)
    {
        return Supplier::latest()->paginate($perPage); // Added and ordered by latest
    }

    public function create(array $data)
    {
        return Supplier::create($data);
    }

    public function find($id)
    {
        return Supplier::findOrFail($id);
    }

    public function update($id, array $data)
    {
        $supplier = Supplier::findOrFail($id);
        $supplier->update($data);
        return $supplier;
    }

    public function delete($id)
    {
        // Using destroy() handles both single and array of IDs, and finds by primary key.
        // It returns the number of records deleted.
        return Supplier::destroy($id);
    }
}