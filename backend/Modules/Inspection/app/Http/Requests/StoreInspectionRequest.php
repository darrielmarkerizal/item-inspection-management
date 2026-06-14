<?php

namespace Modules\Inspection\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Core\Enums\ServiceType;

class StoreInspectionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'service_type' => ['required', Rule::enum(ServiceType::class)],
            'inspection_type_id' => ['nullable', 'integer', 'exists:inspection_types,id'],
            'scope_of_work_id' => ['nullable', 'integer', 'exists:scopes_of_work,id'],
            'location_id' => ['nullable', 'integer', 'exists:locations,id'],
            'customer_id' => ['nullable', 'integer', 'exists:customers,id'],
            'related_to' => ['nullable', 'string', 'max:255'],
            'dvc_code' => ['nullable', 'string', 'max:255'],
            'date_submitted' => ['nullable', 'date'],
            'estimated_completion_date' => ['nullable', 'date'],
            'charge_to_customer' => ['boolean'],
            'note_to_yard' => ['nullable', 'string'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.item_id' => ['required', 'integer', 'exists:items,id'],
            'items.*.qty_required' => ['required', 'numeric', 'min:0'],
            'items.*.inspection_required' => ['boolean'],
            'items.*.lots' => ['required', 'array', 'min:1'],
            'items.*.lots.*.item_lot_id' => ['required', 'integer', 'exists:item_lots,id'],
            'items.*.lots.*.qty' => ['required', 'numeric', 'min:0'],
        ];
    }
}
