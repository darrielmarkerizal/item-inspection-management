<?php

namespace Modules\Inventory\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\MasterData\Models\Allocation;
use Modules\MasterData\Models\Condition;
use Modules\MasterData\Models\Owner;

class ItemLot extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id',
        'lot_no',
        'allocation_id',
        'owner_id',
        'condition_id',
        'available_qty',
    ];

    protected function casts(): array
    {
        return [
            'available_qty' => 'decimal:2',
        ];
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    public function allocation(): BelongsTo
    {
        return $this->belongsTo(Allocation::class);
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(Owner::class);
    }

    public function condition(): BelongsTo
    {
        return $this->belongsTo(Condition::class);
    }
}
