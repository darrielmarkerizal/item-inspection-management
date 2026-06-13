<?php

namespace Modules\Inspection\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Core\Enums\InspectionStatus;
use Modules\Core\Enums\ServiceType;
use Modules\MasterData\Models\Customer;
use Modules\MasterData\Models\InspectionType;
use Modules\MasterData\Models\Location;
use Modules\MasterData\Models\ScopeOfWork;

class Inspection extends Model
{
    use HasFactory;

    protected $fillable = [
        'request_no',
        'service_type',
        'inspection_type_id',
        'scope_of_work_id',
        'location_id',
        'customer_id',
        'related_to',
        'dvc_code',
        'date_submitted',
        'estimated_completion_date',
        'status',
        'note_to_yard',
        'charge_to_customer',
    ];

    protected function casts(): array
    {
        return [
            'service_type' => ServiceType::class,
            'status' => InspectionStatus::class,
            'date_submitted' => 'date',
            'estimated_completion_date' => 'date',
            'charge_to_customer' => 'boolean',
        ];
    }

    public function inspectionType(): BelongsTo
    {
        return $this->belongsTo(InspectionType::class);
    }

    public function scopeOfWork(): BelongsTo
    {
        return $this->belongsTo(ScopeOfWork::class);
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(InspectionItem::class);
    }

    public function charges(): HasMany
    {
        return $this->hasMany(InspectionCharge::class);
    }

    public function statusHistories(): HasMany
    {
        return $this->hasMany(InspectionStatusHistory::class);
    }
}
