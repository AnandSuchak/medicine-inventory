<?php

namespace App\Repositories\Interfaces;

interface BillRepositoryInterface
{
    /**
     * Retrieve all bills.
     */
    public function all();

    /**
     * Find a bill by its ID.
     *
     * @param int $id
     */
    public function find($id);

    /**
     * Create a new bill and deduct stock.
     *
     * @param array $data
     */
    public function create(array $data);

    /**
     * Update an existing bill, adjusting stock accordingly.
     *
     * @param int $id
     * @param array $data
     */
    public function update($id, array $data);

    /**
     * Delete a bill and return its stock to inventory.
     *
     * @param int $id
     */
    public function delete($id);
}