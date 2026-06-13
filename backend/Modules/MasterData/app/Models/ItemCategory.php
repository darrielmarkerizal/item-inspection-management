<?php

namespace Modules\MasterData\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Inventory\Models\Item;

class ItemCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(Item::class);
    }
}
