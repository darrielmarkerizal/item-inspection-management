<?php

namespace Modules\Inventory\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\MasterData\Http\Resources\AllocationResource;
use Modules\MasterData\Http\Resources\ConditionResource;
use Modules\MasterData\Http\Resources\OwnerResource;

class ItemLotResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'lot_no' => $this->lot_no,
            'available_qty' => $this->available_qty,
            'allocation' => $this->allocation ? new AllocationResource($this->allocation) : null,
            'owner' => $this->owner ? new OwnerResource($this->owner) : null,
            'condition' => $this->condition ? new ConditionResource($this->condition) : null,
        ];
    }
}
