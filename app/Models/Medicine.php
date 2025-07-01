<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Medicine extends Model
{
    use HasFactory;

    protected $table = 'medicines';

    protected $fillable = [
        'name',
        'unit',
        'description',
        'gst',
        'pack_size',
        'mfg_company_name',
        'hsn_code', // Make sure this exists from previous steps
    ];
    
    // Relationship to the actual inventory stock
    public function medicineBatches(): HasMany
    {
        return $this->hasMany(MedicineBatch::class);
    }

    public function purchaseBills(): BelongsToMany
    {
        // Corrected withPivot
        return $this->belongsToMany(PurchaseBill::class, 'purchase_bill_medicine')
                    ->withPivot('quantity', 'price', 'discount_percentage', 'batch_no')
                    ->withTimestamps();
    }

    public function billItems(): HasMany
    {
        return $this->hasMany(BillItem::class);
    }
}