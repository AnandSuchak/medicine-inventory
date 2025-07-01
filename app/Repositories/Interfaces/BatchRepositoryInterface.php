<?php

namespace App\Repositories\Interfaces;

interface BatchRepositoryInterface
{
    public function allWithSupplierAndMedicineCount();
    public function create(array $data);
    public function find($id);
    public function update($id, array $data); // Added this
    public function delete($batch);
}