<?php

namespace Modules\Inventory\Services;

use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Modules\Inventory\Http\Resources\ItemResource;
use Modules\Inventory\Repositories\ItemRepository;

class ItemService
{
    public function __construct(private readonly ItemRepository $repository) {}

    public function all(): AnonymousResourceCollection
    {
        return ItemResource::collection($this->repository->allWithLots());
    }
}
