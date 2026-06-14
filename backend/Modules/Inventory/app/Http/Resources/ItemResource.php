<?php

namespace Modules\Inventory\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\MasterData\Http\Resources\ItemCategoryResource;

class ItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'description' => $this->description,
            'unit' => $this->unit,
            'category' => $this->category ? new ItemCategoryResource($this->category) : null,
            'lots' => ItemLotResource::collection($this->whenLoaded('lots')),
        ];
    }
}
