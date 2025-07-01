<?php

namespace App\Repositories\Interfaces;

interface BatchMedicineRepositoryInterface
{
    public function attachMedicinesToBatch($batchId, array $medicines);
    public function updateMedicinesInBatch($batchId, array $medicines);
    public function removeMedicineFromBatch($batchId, $medicineId);
}
