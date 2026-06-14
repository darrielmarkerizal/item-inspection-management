<?php

namespace Modules\Inspection\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\MasterData\Http\Resources\CustomerResource;
use Modules\MasterData\Http\Resources\InspectionTypeResource;
use Modules\MasterData\Http\Resources\LocationResource;
use Modules\MasterData\Http\Resources\ScopeOfWorkResource;

class InspectionResource extends JsonResource
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
            'inspection_type' => $this->inspectionType ? new InspectionTypeResource($this->inspectionType) : null,
            'scope_of_work' => $this->scopeOfWork ? new ScopeOfWorkResource($this->scopeOfWork) : null,
            'location' => $this->location ? new LocationResource($this->location) : null,
            'customer' => $this->customer ? new CustomerResource($this->customer) : null,
            'related_to' => $this->related_to,
            'dvc_code' => $this->dvc_code,
            'date_submitted' => $this->date_submitted?->toDateString(),
            'estimated_completion_date' => $this->estimated_completion_date?->toDateString(),
            'status' => [
                'value' => $this->status?->value,
                'label' => $this->status?->label(),
            ],
            'charge_to_customer' => $this->charge_to_customer,
            'note_to_yard' => $this->note_to_yard,
            'items' => InspectionItemResource::collection($this->whenLoaded('items')),
            'charges' => InspectionChargeResource::collection($this->whenLoaded('charges')),
            'status_histories' => InspectionStatusHistoryResource::collection($this->whenLoaded('statusHistories')),
        ];
    }
}
