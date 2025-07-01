<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'shop_name', // Renamed from 'name'
        'phone',
        'email',
        'address',
        'gst',       // Added
        'pan',       // Added
    ];

    /**
     * Get the bills for the customer.
     */
    public function bills(): HasMany
    {
        return $this->hasMany(Bill::class);
    }
}