<?php

namespace Modules\Inspection\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Core\Enums\InspectionStatus;

class InspectionStatusHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'inspection_id',
        'from_status',
        'to_status',
        'changed_at',
    ];

    protected function casts(): array
    {
        return [
            'from_status' => InspectionStatus::class,
            'to_status' => InspectionStatus::class,
            'changed_at' => 'datetime',
        ];
    }

    public function inspection(): BelongsTo
    {
        return $this->belongsTo(Inspection::class);
    }
}
