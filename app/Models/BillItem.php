<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\BatchMedicine;

class BillItem extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'bill_id',
        'medicine_id',
        'batch_id',
        'quantity',
        'unit_price',
        'gst_rate_percentage',
        'item_gst_amount',
        'sub_total',
        'total_amount_after_tax',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'quantity' => 'integer',
        'unit_price' => 'decimal:2',
        'gst_rate_percentage' => 'decimal:2',
        'item_gst_amount' => 'decimal:2',
        'sub_total' => 'decimal:2',
        'total_amount_after_tax' => 'decimal:2',
    ];

    /**
     * Get the bill that owns the bill item.
     */
    public function bill()
    {
        return $this->belongsTo(Bill::class);
    }

    /**
     * Get the medicine associated with the bill item.
     */
    public function medicine()
    {
        return $this->belongsTo(Medicine::class);
    }

    /**
     * Get the specific batch medicine record associated with this bill item.
     * This links to the 'batch_medicines' table using the 'batch_id' foreign key.
     */
    public function batchMedicine() // Renamed to 'batchMedicine'
    {
        // Specify the foreign key 'batch_id' on bill_items table
        // and the related model BatchMedicine::class
        return $this->belongsTo(BatchMedicine::class, 'batch_id');
    }
}