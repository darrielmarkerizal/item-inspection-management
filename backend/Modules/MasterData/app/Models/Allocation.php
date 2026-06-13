<?php

namespace Modules\MasterData\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Inventory\Models\ItemLot;

class Allocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function itemLots(): HasMany
    {
        return $this->hasMany(ItemLot::class);
    }
}
