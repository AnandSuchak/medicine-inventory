<?php

namespace App\Repositories\Eloquent;

use App\Models\Bill;
use App\Models\BillItem;
use App\Models\Medicine;
use App\Models\MedicineBatch;
use App\Repositories\Interfaces\BillRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BillRepository implements BillRepositoryInterface
{
    public function all()
    {
        return Bill::with('customer')->latest()->get();
    }

    public function find($id)
    {
        return Bill::with('customer', 'billItems.medicine', 'billItems.medicineBatch')->findOrFail($id);
    }

    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            $bill = Bill::create([
                'customer_id' => $data['customer_id'],
                'bill_date' => Carbon::now(),
                'total_amount' => 0,
                'discount' => 0,
                'net_amount' => 0,
            ]);

            $totalAmount = 0;

            foreach ($data['medicines'] as $item) {
                $medicine = Medicine::find($item['id']);
                $quantityToSell = (int)$item['quantity'];

                $availableStocks = MedicineBatch::where('medicine_id', $medicine->id)
                    ->where('quantity', '>', 0)
                    ->orderBy('expiry_date', 'asc')
                    ->lockForUpdate()
                    ->get();

                if ($availableStocks->sum('quantity') < $quantityToSell) {
                    throw new \Exception("Not enough stock available for " . $medicine->name);
                }

                foreach ($availableStocks as $stock) {
                    if ($quantityToSell <= 0) break;

                    $quantityFromThisStock = min($quantityToSell, $stock->quantity);

                    BillItem::create([
                        'bill_id' => $bill->id,
                        'medicine_id' => $medicine->id,
                        'medicine_batch_id' => $stock->id,
                        'quantity' => $quantityFromThisStock,
                        'price' => $item['price'],
                        'discount' => $item['discount'] ?? 0,
                    ]);

                    $stock->decrement('quantity', $quantityFromThisStock);
                    $quantityToSell -= $quantityFromThisStock;

                    $lineSubtotal = $item['price'] * $quantityFromThisStock;
                    $lineDiscountAmount = $lineSubtotal * (($item['discount'] ?? 0) / 100);
                    $totalAmount += ($lineSubtotal - $lineDiscountAmount);
                }
            }

            $bill->total_amount = $totalAmount;
            $bill->net_amount = $totalAmount;
            $bill->save();

            return $bill;
        });
    }

    public function update($id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $bill = $this->find($id);

            // 1. Return all stock from the original bill items
            foreach ($bill->billItems as $oldItem) {
                if ($oldItem->medicineBatch) {
                    $oldItem->medicineBatch->increment('quantity', $oldItem->quantity);
                }
            }

            // 2. Delete the old bill items
            $bill->billItems()->delete();

            // 3. Now, treat it like a new bill creation process
            $totalAmount = 0;
            foreach ($data['medicines'] as $item) {
                $medicine = Medicine::find($item['id']);
                $quantityToSell = (int)$item['quantity'];

                $availableStocks = MedicineBatch::where('medicine_id', $medicine->id)
                    ->where('quantity', '>', 0)
                    ->orderBy('expiry_date', 'asc')
                    ->lockForUpdate()
                    ->get();

                if ($availableStocks->sum('quantity') < $quantityToSell) {
                    throw new \Exception("Not enough stock available for " . $medicine->name);
                }

                foreach ($availableStocks as $stock) {
                    if ($quantityToSell <= 0) break;

                    $quantityFromThisStock = min($quantityToSell, $stock->quantity);

                    BillItem::create([
                        'bill_id' => $bill->id,
                        'medicine_id' => $medicine->id,
                        'medicine_batch_id' => $stock->id,
                        'quantity' => $quantityFromThisStock,
                        'price' => $item['price'],
                        'discount' => $item['discount'] ?? 0,
                    ]);

                    $stock->decrement('quantity', $quantityFromThisStock);
                    $quantityToSell -= $quantityFromThisStock;

                    $lineSubtotal = $item['price'] * $quantityFromThisStock;
                    $lineDiscountAmount = $lineSubtotal * (($item['discount'] ?? 0) / 100);
                    $totalAmount += ($lineSubtotal - $lineDiscountAmount);
                }
            }

            // 4. Update the main bill record with new totals
            $bill->update([
                'customer_id' => $data['customer_id'],
                'total_amount' => $totalAmount,
                'net_amount' => $totalAmount, // Recalculate if there are overall discounts
            ]);

            return $bill;
        });
    }

    public function delete($id)
    {
        return DB::transaction(function () use ($id) {
            $bill = $this->find($id);

            // Return all stock from the bill items before deleting
            foreach ($bill->billItems as $item) {
                if ($item->medicineBatch) {
                    $item->medicineBatch->increment('quantity', $item->quantity);
                }
            }

            // Delete the bill, which will cascade delete the bill items
            return $bill->delete();
        });
    }
}