<?php

namespace Modules\Inspection\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InspectionStatusHistoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'from_status' => $this->from_status ? [
                'value' => $this->from_status->value,
                'label' => $this->from_status->label(),
            ] : null,
            'to_status' => [
                'value' => $this->to_status->value,
                'label' => $this->to_status->label(),
            ],
            'changed_at' => $this->changed_at?->toIso8601String(),
        ];
    }
}
