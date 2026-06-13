<?php

namespace Modules\MasterData\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Core\Enums\ServiceType;
use Modules\Inspection\Models\Inspection;

class ScopeOfWork extends Model
{
    use HasFactory;

    protected $table = 'scopes_of_work';

    protected $fillable = [
        'name',
        'service_type',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'service_type' => ServiceType::class,
        ];
    }

    public function parameters(): BelongsToMany
    {
        return $this->belongsToMany(InspectionParameter::class, 'scope_parameter');
    }

    public function inspections(): HasMany
    {
        return $this->hasMany(Inspection::class);
    }
}
