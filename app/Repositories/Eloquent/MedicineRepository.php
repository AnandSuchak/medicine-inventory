<?php

namespace App\Repositories\Eloquent;

use App\Models\Medicine;
use App\Repositories\Interfaces\MedicineRepositoryInterface;

class MedicineRepository implements MedicineRepositoryInterface
{
    public function allPaginated($perPage = 10)
    {
        return Medicine::latest()->paginate($perPage);
    }

    public function find($id)
    {
        return Medicine::findOrFail($id);
    }

    public function create(array $data)
    {
        return Medicine::create($data);
    }

    public function update($id, array $data)
    {
        $medicine = Medicine::findOrFail($id);
        $medicine->update($data);
        return $medicine;
    }

    public function delete($id)
    {
        $medicine = Medicine::findOrFail($id);
        return $medicine->delete();
    }
}