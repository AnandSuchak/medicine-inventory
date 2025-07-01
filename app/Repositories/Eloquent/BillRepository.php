<?php

namespace App\Repositories\Eloquent;

use App\Models\Bill;
use App\Models\BillItem;
use App\Models\Medicine;
use App\Models\Batch;
use App\Models\BatchMedicine; // Ensure this is imported
use App\Repositories\Interfaces\BillRepositoryInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Models\Customer;
use Exception;

class BillRepository implements BillRepositoryInterface
{
    /**
     * Get a bill by its ID.
     *
     * @param int $billId
     * @return Bill|null
     */
    public function find(int $billId): ?Bill
    {
        // CORRECTED: Changed 'billItems.batch' to 'billItems.batchMedicine'
        return Bill::with(['customer', 'billItems.medicine', 'billItems.batchMedicine'])->find($billId);
    }

    /**
     * Get all bills.
     *
     * @return Collection<Bill>
     */
    public function getAllBills(): Collection
    {
        return Bill::with('customer')->orderBy('bill_date', 'desc')->get();
    }

    /**
     * Create a new bill and its items, handling stock deduction.
     *
     * @param Customer $customer The customer for whom the bill is being generated.
     * @param array $billDetails An array containing bill header details (e.g., notes, discount_amount).
     * @param array $items An array of arrays, where each inner array represents a medicine item to be billed.
     * Each item array should contain: ['medicine_id' => int, 'quantity' => int]
     * @return Bill
     * @throws \Exception If stock is insufficient or other issues occur during billing.
     */
    public function createBill(Customer $customer, array $billDetails, array $items): Bill
    {
        DB::beginTransaction();

        try {
            $billNumber = 'BILL-' . now()->format('YmdHis') . rand(100, 999);

            $bill = new Bill([
                'bill_number'          => $billNumber,
                'bill_date'            => $billDetails['bill_date'] ?? now()->toDateString(),
                'customer_id'          => $customer->id,
                'discount_amount'      => $billDetails['discount_amount'] ?? 0.00,
                'notes'                => $billDetails['notes'] ?? null,
                'payment_status'       => $billDetails['payment_status'] ?? 'pending',
                'status'               => $billDetails['status'] ?? 'completed',
                'sub_total_before_tax' => 0.00,
                'total_gst_amount'     => 0.00,
                'net_amount'           => 0.00,
            ]);
            $bill->save();

            $totalBillSubTotal = 0.00;
            $totalBillGstAmount = 0.00;

            foreach ($items as $itemData) {
                $medicineId = $itemData['medicine_id'];
                $quantityToDeduct = $itemData['quantity'];

                if ($quantityToDeduct <= 0) {
                    throw new Exception("Quantity for medicine ID {$medicineId} must be positive.");
                }

                $deductions = $this->deductStock($medicineId, $quantityToDeduct);

                foreach ($deductions as $deduction) {
                    $batchId = $deduction['batch_id'];
                    $deductedQuantity = $deduction['quantity'];

                    $batchMedicinePivot = DB::table('batch_medicine')
                                            ->where('medicine_id', $medicineId)
                                            ->where('batch_id', $batchId)
                                            ->first();

                    if (!$batchMedicinePivot) {
                        throw new Exception("Batch-Medicine pivot data not found for Medicine ID: {$medicineId}, Batch ID: {$batchId}");
                    }

                    $unitPrice = $batchMedicinePivot->ptr;
                    $gstRatePercentage = $batchMedicinePivot->gst_percent;

                    $subTotal = $deductedQuantity * $unitPrice;
                    $itemGstAmount = ($subTotal * $gstRatePercentage) / 100;
                    $totalAmountAfterTax = $subTotal + $itemGstAmount;

                    $billItem = new BillItem([
                        'bill_id'              => $bill->id,
                        'medicine_id'          => $medicineId,
                        'batch_id'             => $batchId,
                        'quantity'             => $deductedQuantity,
                        'unit_price'           => $unitPrice,
                        'gst_rate_percentage'  => $gstRatePercentage,
                        'item_gst_amount'      => $itemGstAmount,
                        'sub_total'            => $subTotal,
                        'total_amount_after_tax' => $totalAmountAfterTax,
                    ]);
                    $billItem->save();

                    $totalBillSubTotal += $subTotal;
                    $totalBillGstAmount += $itemGstAmount;
                }
            }

            $netAmount = ($totalBillSubTotal + $totalBillGstAmount) - $bill->discount_amount;

            $bill->update([
                'sub_total_before_tax' => $totalBillSubTotal,
                'total_gst_amount'     => $totalBillGstAmount,
                'net_amount'           => $netAmount,
            ]);

            DB::commit();

            return $bill;

        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

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
    public function updateBill(int $billId, array $billDetails, array $items, array $deletedItemIds = []): Bill
    {
        return DB::transaction(function () use ($billId, $billDetails, $items, $deletedItemIds) {
            $bill = Bill::with('billItems')->findOrFail($billId);

            // Update bill header details
            $bill->customer_id = $billDetails['customer_id'];
            $bill->bill_date = $billDetails['bill_date'];
            $bill->notes = $billDetails['notes'] ?? null;
            $bill->discount_amount = $billDetails['discount_amount'] ?? 0;
            $bill->status = $billDetails['status'] ?? $bill->status;
            $bill->save();

            // 1. Return stock for deleted items
            foreach ($deletedItemIds as $deletedItemId) {
                $deletedItem = BillItem::find($deletedItemId);
                if ($deletedItem) {
                    $this->returnStock($deletedItem->medicine_id, $deletedItem->quantity, $deletedItem->batch_id);
                    $deletedItem->delete();
                }
            }

            // 2. Process current items (update existing or create new)
            $existingItemIds = [];
            foreach ($items as $itemData) {
                $billItem = null;
                if (isset($itemData['id']) && $itemData['id']) {
                    $billItem = BillItem::find($itemData['id']);
                }

                if ($billItem) {
                    // Existing item: Check for quantity changes and adjust stock
                    $oldQuantity = $billItem->quantity;
                    $newQuantity = $itemData['quantity'];

                    if ($oldQuantity !== $newQuantity) {
                        $quantityDifference = $newQuantity - $oldQuantity;

                        if ($quantityDifference > 0) {
                            // Quantity increased, deduct more stock
                            $this->deductStock($itemData['medicine_id'], $quantityDifference);
                        } else {
                            // Quantity decreased, return stock
                            $this->returnStock($itemData['medicine_id'], abs($quantityDifference), $billItem->batch_id);
                        }
                    }
                    // Update item details
                    $billItem->fill([
                        'medicine_id' => $itemData['medicine_id'],
                        'quantity' => $itemData['quantity'],
                        'unit_price' => $itemData['unit_price'],
                        'gst_rate_percentage' => $itemData['gst_rate_percentage'],
                        'item_gst_amount' => $itemData['item_gst_amount'],
                        'sub_total' => $itemData['sub_total'],
                        'total_amount_after_tax' => $itemData['total_amount_after_tax'],
                        'batch_id' => $itemData['batch_id'] ?? $billItem->batch_id,
                    ]);
                    $billItem->save();
                    $existingItemIds[] = $billItem->id;

                } else {
                    // New item: Deduct stock and create new item
                    $medicine = Medicine::find($itemData['medicine_id']);
                    if (!$medicine) {
                        throw new Exception("Medicine not found for ID: " . $itemData['medicine_id']);
                    }

                    $deductedBatches = $this->deductStock($itemData['medicine_id'], $itemData['quantity']);
                    $batchIdUsed = $deductedBatches[0]['batch_id'] ?? null;

                    $newBillItem = new BillItem([
                        'bill_id' => $bill->id,
                        'medicine_id' => $itemData['medicine_id'],
                        'quantity' => $itemData['quantity'],
                        'unit_price' => $itemData['unit_price'],
                        'gst_rate_percentage' => $itemData['gst_rate_percentage'],
                        'item_gst_amount' => $itemData['item_gst_amount'],
                        'sub_total' => $itemData['sub_total'],
                        'total_amount_after_tax' => $itemData['total_amount_after_tax'],
                        'batch_id' => $batchIdUsed,
                    ]);
                    $bill->billItems()->save($newBillItem);
                    $existingItemIds[] = $newBillItem->id;
                }
            }

            $this->recalculateBillTotals($bill);

            return $bill->fresh('billItems.medicine', 'billItems.batchMedicine', 'customer'); // Corrected eager load
        });
    }

    /**
     * Handle the stock deduction process for a single medicine item, potentially across multiple batches (FIFO).
     * This method is called internally by createBill().
     *
     * @param int $medicineId The ID of the medicine.
     * @param int $quantityToDeduct The total quantity to deduct for this medicine.
     * @return array An array of arrays, each containing 'batch_id' and 'quantity' deducted from that batch.
     * @throws \Exception If insufficient stock is found.
     */
    public function deductStock(int $medicineId, int $quantityToDeduct): array
    {
        $medicine = Medicine::find($medicineId);
        if (!$medicine) {
            throw new Exception("Medicine with ID {$medicineId} not found.");
        }

        $availableBatches = DB::table('batch_medicine')
            ->where('medicine_id', $medicineId)
            ->where('quantity', '>', 0)
            ->join('batches', 'batch_medicine.batch_id', '=', 'batches.id')
            ->orderBy('batch_medicine.expiry_date', 'asc')
            ->select('batch_medicine.batch_id', 'batch_medicine.quantity', 'batch_medicine.id as pivot_id')
            ->get();

        $currentDeducted = 0;
        $deductions = [];

        foreach ($availableBatches as $batchPivot) {
            $remainingToDeduct = $quantityToDeduct - $currentDeducted;

            if ($remainingToDeduct <= 0) {
                break;
            }

            $quantityFromThisBatch = min($remainingToDeduct, $batchPivot->quantity);

            DB::table('batch_medicine')
                ->where('id', $batchPivot->pivot_id)
                ->decrement('quantity', $quantityFromThisBatch);

            $deductions[] = [
                'batch_id' => $batchPivot->batch_id,
                'quantity' => $quantityFromThisBatch,
            ];

            $currentDeducted += $quantityFromThisBatch;
        }

        if ($currentDeducted < $quantityToDeduct) {
            throw new Exception("Insufficient stock for medicine ID {$medicineId}. Needed: {$quantityToDeduct}, Available: {$currentDeducted}.");
        }

        return $deductions;
    }

    /**
     * Helper method to return stock to a specific batch.
     *
     * @param int $medicineId
     * @param int $quantityToReturn
     * @param int|null $batchId If provided, return to this specific batch.
     * @return void
     * @throws Exception
     */
    private function returnStock(int $medicineId, int $quantityToReturn, ?int $batchId = null): void
    {
        if ($quantityToReturn <= 0) {
            return;
        }

        if ($batchId) {
            $batchMedicine = DB::table('batch_medicine')
                ->where('medicine_id', $medicineId)
                ->where('batch_id', $batchId)
                ->first();

            if ($batchMedicine) {
                DB::table('batch_medicine')
                    ->where('id', $batchMedicine->id)
                    ->increment('quantity', $quantityToReturn);
            } else {
                throw new Exception("Batch Medicine record not found for returning stock (Medicine ID: $medicineId, Batch ID: $batchId).");
            }
        } else {
            // Fallback for returning stock if original batch not specified.
            // This could involve finding the most appropriate batch to return to (e.g., non-expired, existing).
            // For now, we'll return to the earliest expiring batch if a specific one isn't provided.
            $earliestBatch = DB::table('batch_medicine')
                ->where('medicine_id', $medicineId)
                ->orderBy('expiry_date', 'asc')
                ->first();

            if ($earliestBatch) {
                DB::table('batch_medicine')
                    ->where('id', $earliestBatch->id)
                    ->increment('quantity', $quantityToReturn);
            } else {
                throw new Exception("No suitable batch found to return stock for Medicine ID: $medicineId.");
            }
        }
    }

    /**
     * Recalculate and update bill totals based on its items (useful after modifications).
     *
     * @param Bill $bill
     * @return Bill
     */
    public function recalculateBillTotals(Bill $bill): Bill
    {
        $bill->load('billItems');

        $totalSubTotal = 0.00;
        $totalGstAmount = 0.00;

        foreach ($bill->billItems as $item) {
            $totalSubTotal += $item->sub_total;
            $totalGstAmount += $item->item_gst_amount;
        }

        $netAmount = ($totalSubTotal + $totalGstAmount) - $bill->discount_amount;

        $bill->update([
            'sub_total_before_tax' => $totalSubTotal,
            'total_gst_amount'     => $totalGstAmount,
            'net_amount'           => $netAmount,
        ]);

        return $bill;
    }

    /**
     * Get the total available stock, PTR, and GST% for a given medicine from the earliest expiring batch.
     *
     * @param int $medicineId
     * @return array Contains 'total_available_quantity', 'unit_price' (from first batch's PTR), 'gst_rate_percentage' (from first batch's GST%).
     */
    public function getMedicineStockInfo(int $medicineId): array
    {
        $medicine = Medicine::find($medicineId);
        if (!$medicine) {
            return [
                'total_available_quantity' => 0,
                'unit_price' => 0.00,
                'gst_rate_percentage' => 0.00
            ];
        }

        $batches = DB::table('batch_medicine')
            ->where('medicine_id', $medicineId)
            ->where('quantity', '>', 0)
            ->orderBy('expiry_date', 'asc')
            ->select('quantity', 'ptr', 'gst_percent')
            ->get();

        $totalAvailableQuantity = $batches->sum('quantity');

        $firstBatch = $batches->first();

        return [
            'total_available_quantity' => $totalAvailableQuantity,
            'unit_price' => $firstBatch ? $firstBatch->ptr : 0.00,
            'gst_rate_percentage' => $firstBatch ? $firstBatch->gst_percent : 0.00
        ];
    }
}
