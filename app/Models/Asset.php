<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Asset extends Model
{
    use HasFactory;

    protected $fillable = [
        'asset_code',
        'name',
        'category_id',
        'location_id',
        'brand',
        'model_type',
        'serial_number',
        'purchase_date',
        'purchase_cost',
        'condition',
        'status',
        'image_path',
        'notes',
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'purchase_cost' => 'decimal:2',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    public function maintenanceLogs(): HasMany
    {
        return $this->hasMany(MaintenanceLog::class)->orderBy('maintenance_date', 'desc');
    }

    public function getImageUrlAttribute(): ?string
    {
        if ($this->image_path) {
            if (str_starts_with($this->image_path, 'http://') || str_starts_with($this->image_path, 'https://')) {
                return $this->image_path;
            }
            if (file_exists(public_path($this->image_path))) {
                return asset($this->image_path);
            }
        }
        return null;
    }
}
