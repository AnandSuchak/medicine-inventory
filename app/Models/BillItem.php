<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BillItem extends Model
{
       protected $fillable = [
        'bill_id',
        'medicine_id',
        'batch_id',
        'quantity',
        'unit_price',
        'total_price'
    ];
    public function bill()
    {
        return $this->belongsTo(Bill::class);
    }

    public function medicine()
    {
        return $this->belongsTo(Medicine::class);
    }

        public function batch()
    {
        return $this->belongsTo(Batch::class);
    }
}

