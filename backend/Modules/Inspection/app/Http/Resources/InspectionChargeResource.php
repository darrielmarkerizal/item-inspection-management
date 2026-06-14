<?php

namespace Modules\Inspection\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InspectionChargeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'order_no' => $this->order_no,
            'service_description' => $this->service_description,
            'qty' => $this->qty,
            'unit_price' => $this->unit_price,
            'total' => $this->total,
        ];
    }
}
