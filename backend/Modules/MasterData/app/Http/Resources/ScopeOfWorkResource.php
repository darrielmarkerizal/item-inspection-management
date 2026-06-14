<?php

namespace Modules\MasterData\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ScopeOfWorkResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'service_type' => $this->service_type?->value,
            'description' => $this->description,
            'parameters' => InspectionParameterResource::collection($this->whenLoaded('parameters')),
        ];
    }
}
