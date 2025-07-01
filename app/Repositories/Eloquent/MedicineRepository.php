<?php

namespace App\Repositories\Eloquent;

use App\Models\Medicine;
use App\Repositories\Interfaces\MedicineRepositoryInterface;

class MedicineRepository implements MedicineRepositoryInterface
{
    public function all()
    {
        return Medicine::latest()->get();
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
        $medicine = $this->find($id);
        $medicine->update($data);
        return $medicine;
    }

    public function delete($id)
    {
        $medicine = $this->find($id);
        return $medicine->delete();
    }

    public function search($query)
    {
        return Medicine::where('name', 'like', "%{$query}%")->get();
    }
}