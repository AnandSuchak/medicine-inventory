<?php

namespace App\Repositories\Interfaces;

interface CustomerRepositoryInterface
{
    public function allPaginated($perPage = 10);
    public function create(array $data);
    public function update($customer, array $data);
    public function delete($customer);
      public function findById(int $id);
}
