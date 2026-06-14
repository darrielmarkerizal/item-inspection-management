<?php

namespace Modules\Inspection\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Core\Enums\ServiceType;
use Modules\Inventory\Models\ItemLot;

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
            'items.*.lots' => ['required', 'array', 'min:1'],
            'items.*.lots.*.item_lot_id' => ['required', 'integer', 'exists:item_lots,id'],
            'items.*.lots.*.qty_required' => ['required', 'numeric', 'min:0'],
            'items.*.lots.*.inspection_required' => ['boolean'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            foreach ($this->input('items', []) as $itemIndex => $item) {
                foreach ($item['lots'] ?? [] as $lotIndex => $lot) {
                    $lotId = $lot['item_lot_id'] ?? null;
                    $qty = $lot['qty_required'] ?? null;

                    if ($lotId === null || $qty === null) {
                        continue;
                    }

                    $available = ItemLot::whereKey($lotId)->value('available_qty');

                    if ($available !== null && (float) $qty > (float) $available) {
                        $validator->errors()->add(
                            "items.{$itemIndex}.lots.{$lotIndex}.qty_required",
                            "Qty required ({$qty}) cannot exceed available qty ({$available})."
                        );
                    }
                }
            }
        });
    }
}
