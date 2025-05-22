<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    protected $fillable = ['name', 'hsn_code', 'description', 'quantity'];

public function batches()
{
    return $this->belongsToMany(Batch::class)
                ->withPivot('quantity', 'price', 'ptr', 'expiry_date')
                ->withTimestamps();
}


    public function billItems()
    {
        return $this->hasMany(BillItem::class);
    }
public function bills()
{
    return $this->belongsToMany(Bill::class)
        ->withPivot('quantity', 'unit_price', 'total_price')
        ->withTimestamps();
}


}

