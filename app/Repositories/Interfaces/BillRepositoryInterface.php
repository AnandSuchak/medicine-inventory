<?php

namespace App\Repositories\Interfaces;

use Illuminate\Support\Collection; // For type-hinting collections
use App\Models\Bill; // For type-hinting Bill model
use App\Models\Customer; // For type-hinting Customer model

interface BillRepositoryInterface
{
    /**
     * Get a bill by its ID.
     *
     * @param int $billId
     * @return Bill|null
     */
    public function find(int $billId): ?Bill;

    /**
     * Get all bills.
     *
     * @return Collection<Bill>
     */
    public function getAllBills(): Collection;

    /**
     * Create a new bill and its items, handling stock deduction.
     *
     * @param Customer $customer The customer for whom the bill is being generated.
     * @param array $billDetails An array containing bill header details (e.g., notes, discount_amount).
     * @param array $items An array of arrays, where each inner array represents a medicine item to be billed.
     * Each item array should contain:
     * [
     * 'medicine_id' => int,
     * 'quantity' => int,
     * // Additional item-specific details like unit_price, gst_rate_percentage
     * // will be derived from batch_medicine data within the repository.
     * ]
     * @return Bill
     * @throws \Exception If stock is insufficient or other issues occur during billing.
     */
    public function createBill(Customer $customer, array $billDetails, array $items): Bill;

    /**
     * Handle the stock deduction process for a single medicine item, potentially across multiple batches.
     * This method would be called internally by createBill().
     *
     * @param int $medicineId The ID of the medicine.
     * @param int $quantityToDeduct The total quantity to deduct for this medicine.
     * @return array An array of batch_medicine IDs and quantities deducted from each.
     * @throws \Exception If insufficient stock is found.
     */
    public function deductStock(int $medicineId, int $quantityToDeduct): array;

    /**
     * Recalculate and update bill totals based on its items (useful after modifications).
     *
     * @param Bill $bill
     * @return Bill
     */
    public function recalculateBillTotals(Bill $bill): Bill;
    
        /**
     * Get the total available stock, PTR, and GST% for a given medicine from the earliest expiring batch.
     * This is used for display purposes on the billing form.
     *
     * @param int $medicineId
     * @return array Contains 'total_available_quantity', 'unit_price' (from first batch's PTR), 'gst_rate_percentage' (from first batch's GST%).
     */
    public function getMedicineStockInfo(int $medicineId): array;

      /**
     * Update an existing bill and its items, handling stock adjustments.
     *
     * @param int $billId The ID of the bill to update.
     * @param array $billDetails An array containing bill header details (e.g., notes, discount_amount, customer_id, bill_date).
     * @param array $items An array of arrays, where each inner array represents an updated or new medicine item.
     * Each item array should contain:
     * [
     * 'id' => int|null (existing item ID, or null for new items),
     * 'medicine_id' => int,
     * 'quantity' => int,
     * // other item details like unit_price, gst_rate_percentage
     * ]
     * @param array $deletedItemIds An array of IDs of bill items that were removed from the bill.
     * @return Bill
     * @throws \Exception If stock is insufficient or other issues occur during update.
     */
    public function updateBill(int $billId, array $billDetails, array $items, array $deletedItemIds = []): Bill;

}