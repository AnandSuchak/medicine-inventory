<?php

namespace App\Repositories\Interfaces;

interface MedicineRepositoryInterface
{
    public function allPaginated($perPage = 10);
    public function find($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
}