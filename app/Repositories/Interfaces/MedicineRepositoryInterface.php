<?php

namespace App\Repositories\Interfaces;

interface MedicineRepositoryInterface
{
    /**
     * Retrieve all medicines.
     */
    public function all();

    /**
     * Find a medicine by its ID.
     *
     * @param int $id
     */
    public function find($id);

    /**
     * Create a new medicine.
     *
     * @param array $data
     */
    public function create(array $data);

    /**
     * Update an existing medicine.
     *
     * @param int $id
     * @param array $data
     */
    public function update($id, array $data);

    /**
     * Delete a medicine by its ID.
     *
     * @param int $id
     */
    public function delete($id);

    /**
     * Search for medicines by name.
     *
     * @param string $query
     */
    public function search($query);
}