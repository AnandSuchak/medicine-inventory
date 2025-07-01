<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany; // Add this for billItems()

class Batch extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'batches';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'batch_number',
        'mfg_date',
        'notes',
        'status',
        'purchase_date',
        'supplier_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'mfg_date'      => 'date',
        'purchase_date' => 'date',
    ];

    // --- Relationships ---

    /**
     * Get the medicines associated with the batch (Many-to-Many).
     */
    public function medicines(): BelongsToMany
    {
        return $this->belongsToMany(Medicine::class)
                    ->withPivot('quantity', 'price', 'ptr', 'gst_percent', 'expiry_date')
                    ->withTimestamps();
    }

    /**
     * Get the supplier that owns the batch (BelongsTo).
     */
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    /**
     * Get the bill items that belong to this batch.
     * This is useful for tracking sales from a specific batch.
     */
    public function billItems(): HasMany // Add this new relationship
    {
        return $this->hasMany(BillItem::class);
    }
}