<?php

namespace Modules\Inspection\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InspectionListResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'request_no' => $this->request_no,
            'service_type' => [
                'value' => $this->service_type?->value,
                'label' => $this->service_type?->label(),
            ],
            'inspection_type' => $this->inspectionType?->name,
            'scope_of_work' => $this->scopeOfWork?->name,
            'location' => $this->location?->name,
            'customer' => $this->customer?->name,
            'related_to' => $this->related_to,
            'date_submitted' => $this->date_submitted?->toDateString(),
            'estimated_completion_date' => $this->estimated_completion_date?->toDateString(),
            'status' => [
                'value' => $this->status?->value,
                'label' => $this->status?->label(),
            ],
            'items_count' => $this->items_count,
        ];
    }
}
