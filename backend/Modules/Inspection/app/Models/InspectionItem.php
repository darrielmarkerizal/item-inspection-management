<?php

namespace Modules\Inspection\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Inventory\Models\Item;

class InspectionItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'inspection_id',
        'item_id',
        'item_description',
        'qty_required',
        'inspection_required',
    ];

    protected function casts(): array
    {
        return [
            'qty_required' => 'decimal:2',
            'inspection_required' => 'boolean',
        ];
    }

    public function inspection(): BelongsTo
    {
        return $this->belongsTo(Inspection::class);
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    public function lots(): HasMany
    {
        return $this->hasMany(InspectionItemLot::class);
    }
}
