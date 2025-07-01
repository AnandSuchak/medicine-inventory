<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany; // Required for the batches() relationship

class Supplier extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'suppliers';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'contact_person', // Assuming this field exists or will be added
        'phone',
        'email',
        'address',
        'gstin',
        'drug_license_id', // Add this new field
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        // No specific casts needed for typical Supplier model
    ];

    // --- Relationships ---

    /**
     * Get the batches provided by the supplier.
     * Defines the one-to-many relationship with Batch model.
     *
     * @return HasMany
     */
    public function batches(): HasMany
    {
        return $this->hasMany(Batch::class);
    }
}
