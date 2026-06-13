<?php

namespace Modules\Inventory\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\MasterData\Models\ItemCategory;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_category_id',
        'code',
        'description',
        'unit',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(ItemCategory::class, 'item_category_id');
    }

    public function lots(): HasMany
    {
        return $this->hasMany(ItemLot::class);
    }
}
