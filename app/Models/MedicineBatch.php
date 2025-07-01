<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MedicineBatch extends Model
{
    use HasFactory;

    protected $table = 'medicine_batches';

    protected $fillable = [
        'medicine_id',
        'batch_no',
        'expiry_date',
        'quantity',
        'purchase_price',
        'ptr',
        'mrp',
    ];

    protected $casts = [
        'expiry_date' => 'date',
    ];

    /**
     * Get the medicine that this batch belongs to.
     */
    public function medicine(): BelongsTo
    {
        return $this->belongsTo(Medicine::class);
    }
}