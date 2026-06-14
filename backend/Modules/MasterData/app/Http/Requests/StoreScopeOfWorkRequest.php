<?php

namespace Modules\MasterData\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Core\Enums\ServiceType;

class StoreScopeOfWorkRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', 'unique:scopes_of_work,name'],
            'service_type' => ['required', Rule::enum(ServiceType::class)],
            'description' => ['nullable', 'string'],
            'parameter_ids' => ['nullable', 'array'],
            'parameter_ids.*' => ['integer', 'exists:inspection_parameters,id'],
        ];
    }
}
