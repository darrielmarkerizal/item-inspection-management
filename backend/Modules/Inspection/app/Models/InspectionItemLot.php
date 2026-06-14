<?php

namespace Modules\Inspection\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Inventory\Models\ItemLot;

class InspectionItemLot extends Model
{
    use HasFactory;

    protected $fillable = [
        'inspection_item_id',
        'item_lot_id',
        'lot_no',
        'allocation',
        'owner',
        'condition',
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

    public function inspectionItem(): BelongsTo
    {
        return $this->belongsTo(InspectionItem::class);
    }

    public function itemLot(): BelongsTo
    {
        return $this->belongsTo(ItemLot::class);
    }
}
