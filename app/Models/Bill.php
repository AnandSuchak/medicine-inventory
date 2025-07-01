<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\BillItem;

class Bill extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'bill_number',
        'bill_date',
        'customer_id',
        'sub_total_before_tax',
        'total_gst_amount',
        'discount_amount',
        'net_amount',
        'payment_status',
        'notes',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'bill_date' => 'date',
        'sub_total_before_tax' => 'decimal:2',
        'total_gst_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'net_amount' => 'decimal:2',
    ];

    /**
     * Get the customer that owns the bill.
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the bill items for the bill.
     */
    public function billItems() // Keep this name for consistency
    {
        return $this->hasMany(BillItem::class);
    }
}