<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany; // Add this for billItems()

class Medicine extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'medicines';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'unit',
        'description',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        // No specific casts needed for typical Medicine model
    ];

    // --- Relationships ---

    /**
     * Get the batches that the medicine belongs to.
     * Defines the many-to-many relationship with Batch model.
     */
    public function batches(): BelongsToMany
    {
        return $this->belongsToMany(Batch::class)
                    ->withPivot('quantity', 'price', 'ptr', 'gst_percent', 'expiry_date')
                    ->withTimestamps();
    }

    /**
     * Get the bill items that belong to this medicine.
     * This is useful for tracking sales of a specific medicine.
     */
    public function billItems(): HasMany // Add this new relationship
    {
        return $this->hasMany(BillItem::class);
    }

        /**
     * Get the batch medicines associated with the Medicine.
     * This establishes a one-to-many relationship: One Medicine can be in many BatchMedicine records.
     */
    public function batchMedicines()
    {
        // Assuming 'medicine_id' is the foreign key in the 'batch_medicines' table
        return $this->hasMany(BatchMedicine::class);
    }
}