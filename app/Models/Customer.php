<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    // The attributes that are mass assignable
    protected $fillable = [
        'name',
        'phone',
        'email',
        'address',
    ];
    /**
     * Get the bills for the customer.
     */
    public function bills()
    {
        return $this->hasMany(Bill::class);
    }
}
