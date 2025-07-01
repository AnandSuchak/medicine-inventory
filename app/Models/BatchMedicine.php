<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BatchMedicine extends Model
{
    use HasFactory;

    protected $table = 'batch_medicine';

    protected $fillable = [
        'batch_id',
        'medicine_id',
        'quantity',
        'price',
        'ptr',
        'gst_percent', // <--- Added this
        'expiry_date',
    ];

    protected $casts = [
        'expiry_date' => 'date',
    ];

    public function batch(): BelongsTo
    {
        return $this->belongsTo(Batch::class);
    }

    public function medicine(): BelongsTo
    {
        return $this->belongsTo(Medicine::class);
    }
}