<?php

namespace Modules\Inventory\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Modules\Inventory\Models\Item;

class ItemRepository
{
    public function allWithLots(): Collection
    {
        return Item::with(['category', 'lots.allocation', 'lots.owner', 'lots.condition'])
            ->orderBy('code')
            ->get();
    }
}
