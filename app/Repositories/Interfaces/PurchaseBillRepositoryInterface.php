<?php

namespace App\Repositories\Interfaces;

interface PurchaseBillRepositoryInterface
{
    /**
     * Retrieve all purchase bills.
     */
    public function all();

    /**
     * Find a purchase bill by its ID.
     *
     * @param int $id
     */
    public function find($id); // Corrected: removed the duplicate 'public'

    /**
     * Create a new purchase bill.
     *
     * @param array $data
     */
    public function create(array $data);

    /**
     * Update an existing purchase bill.
     *
     * @param int $id
     * @param array $data
     */
    public function update($id, array $data);

    /**
     * Delete a purchase bill by its ID.
     *
     * @param int $id
     */
    public function delete($id);
}