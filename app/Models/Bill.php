<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    protected $fillable = [
        'bill_number',
        'customer_id',
        'subtotal',
        'gst',
        'discount',
        'grand_total',
        'status'
    ];

    /**
     * One-to-many relationship: Bill has many BillItems.
     */
    public function items()
    {
        return $this->hasMany(BillItem::class);
    }

    /**
     * Bill belongs to a Customer.
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Many-to-many relationship with medicines via pivot table.
     */
    public function medicines()
    {
        return $this->belongsToMany(Medicine::class)
            ->withPivot('quantity', 'unit_price', 'total_price')
            ->withTimestamps();
    }

    /**
     * Boot method to generate bill_number before creating a new Bill.
     */
    protected static function boot()
    {
        parent::boot();

       static::creating(function ($bill) {
            $latestId = self::max('id') + 1;
            $bill->bill_number = 'BILL-' . now()->format('Ymd') . '-' . str_pad($latestId, 4, '0', STR_PAD_LEFT);
            $bill->status = $bill->status ?? 'Ordered'; // fallback if not set
        });
    }
}
