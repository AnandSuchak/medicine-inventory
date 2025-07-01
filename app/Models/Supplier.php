<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Supplier extends Model
{
    use HasFactory;

    protected $table = 'suppliers';

    protected $fillable = [
        'name',
        'phone',
        'email',
        'address',
        'gstin',
        'drug_license',
    ];

    /**
     * Get the purchase bills provided by the supplier.
     */
    public function purchaseBills(): HasMany
    {
        return $this->hasMany(PurchaseBill::class); // Corrected typo
    }
}