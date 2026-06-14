<?php

namespace Modules\Inspection\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InspectionItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'item' => $this->item ? [
                'id' => $this->item->id,
                'code' => $this->item->code,
                'description' => $this->item->description,
                'unit' => $this->item->unit,
            ] : null,
            'item_description' => $this->item_description,
            'lots' => InspectionItemLotResource::collection($this->whenLoaded('lots')),
        ];
    }
}
