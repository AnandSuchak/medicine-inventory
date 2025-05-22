<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Medicine;

class Batch extends Model
{

      protected $fillable = [
        'batch_number',
        'supplier_id',
        'status',
    ];

public function medicines()
{
    return $this->belongsToMany(Medicine::class)
                ->withPivot('quantity', 'price', 'ptr', 'expiry_date')
                ->withTimestamps();
}



    public function stocks()
    {
        return $this->hasMany(Stock::class);
    }

public function supplier()
{
    return $this->belongsTo(Supplier::class);
}

}
