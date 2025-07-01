<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class PurchaseBill extends Model
{
    use HasFactory;

    protected $table = 'purchase_bills';

    protected $fillable = [
        'batch_number',
        'mfg_date',
        'notes',
        'status',
        'purchase_date',
        'supplier_id',
        'cash_discount_percentage',
        'cgst_amount',
        'sgst_amount',
        'supplier_invoice_no', // Make sure this is added from our previous step
    ];

    protected $casts = [
        'mfg_date'      => 'date',
        'purchase_date' => 'date',
    ];

    /**
     * The medicines recorded on this purchase bill.
     */
    public function medicines(): BelongsToMany
    {
        // Corrected withPivot to only include columns that exist on the pivot table
        return $this->belongsToMany(Medicine::class, 'purchase_bill_medicine')
                    ->withPivot('quantity', 'price', 'discount_percentage', 'batch_no')
                    ->withTimestamps();
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }
}