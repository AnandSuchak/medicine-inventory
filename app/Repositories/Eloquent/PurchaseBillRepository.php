<?php

namespace App\Repositories\Eloquent;

use App\Models\PurchaseBill;
use App\Models\MedicineBatch;
use App\Repositories\Interfaces\PurchaseBillRepositoryInterface;
use Illuminate\Support\Facades\DB;

class PurchaseBillRepository implements PurchaseBillRepositoryInterface
{
    public function all()
    {
        return PurchaseBill::with('supplier')->latest()->get();
    }

    public function find($id)
    {
        return PurchaseBill::with('supplier', 'medicines')->findOrFail($id);
    }

    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            // 1. Create the main Purchase Bill record
            $purchaseBill = PurchaseBill::create([
                'batch_number' => $data['batch_number'],
                'supplier_id' => $data['supplier_id'],
                'purchase_date' => $data['purchase_date'],
                'notes' => $data['notes'] ?? null,
                'cash_discount_percentage' => $data['cash_discount_percentage'] ?? 0,
                'status' => 'active',
                'supplier_invoice_no' => $data['supplier_invoice_no'] ?? null,
            ]);

            // 2. Loop through each medicine item from the form
            foreach ($data['medicines'] as $itemData) {
                // 3. Find or Create the specific manufacturer batch in our inventory
                $medicineBatch = MedicineBatch::firstOrCreate(
                    [
                        'medicine_id' => $itemData['id'],
                        'batch_no'    => $itemData['batch_no'],
                    ],
                    [
                        'expiry_date'    => $itemData['expiry_date'],
                        'purchase_price' => $itemData['price'],
                        'mrp'            => $itemData['mrp'],
                        'ptr'            => $itemData['ptr'],
                        'quantity'       => 0, // Default quantity to 0, we add it next
                    ]
                );

                // 4. Increase the quantity for that batch in our inventory
                $medicineBatch->increment('quantity', $itemData['quantity']);

                // 5. Create a historical record of this transaction on the purchase bill
                $purchaseBill->medicines()->attach($itemData['id'], [
                    'quantity' => $itemData['quantity'],
                    'price' => $itemData['price'],
                    'discount_percentage' => $itemData['discount_percentage'] ?? 0,
                    'batch_no' => $itemData['batch_no'],
                ]);
            }

            return $purchaseBill;
        });
    }

    public function update($id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            // For updates, the simplest and safest approach is to "undo" the old transaction
            // and then create a new one. This avoids complex calculations for quantity changes.

            $oldPurchaseBill = $this->find($id);

            // 1. "Return" the stock from the old purchase bill
            foreach ($oldPurchaseBill->medicines as $medicine) {
                $medicineBatch = MedicineBatch::where('medicine_id', $medicine->id)
                    ->where('batch_no', $medicine->pivot->batch_no)
                    ->first();
                
                if ($medicineBatch) {
                    $medicineBatch->decrement('quantity', $medicine->pivot->quantity);
                }
            }
            
            // 2. Delete the old purchase bill records
            $oldPurchaseBill->medicines()->detach();
            $oldPurchaseBill->delete();

            // 3. Create a new purchase bill with the updated data
            // We reuse the create method, but we need to ensure the batch_number is the same
            $newData = $data;
            $newData['batch_number'] = $oldPurchaseBill->batch_number; // Preserve the original bill number

            return $this->create($newData);
        });
    }

    public function delete($id)
    {
        return DB::transaction(function () use ($id) {
            $purchaseBill = $this->find($id);

            // "Return" the stock before deleting
            foreach ($purchaseBill->medicines as $medicine) {
                 $medicineBatch = MedicineBatch::where('medicine_id', $medicine->id)
                    ->where('batch_no', $medicine->pivot->batch_no)
                    ->first();
                
                if ($medicineBatch) {
                    $medicineBatch->decrement('quantity', $medicine->pivot->quantity);
                }
            }

            $purchaseBill->medicines()->detach();
            return $purchaseBill->delete();
        });
    }
}