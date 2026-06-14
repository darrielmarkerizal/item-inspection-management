<?php

namespace Modules\Inspection\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InspectionItemLotResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'item_lot_id' => $this->item_lot_id,
            'lot_no' => $this->lot_no,
            'allocation' => $this->allocation,
            'owner' => $this->owner,
            'condition' => $this->condition,
            'qty' => $this->qty,
        ];
    }
}
