<?php

namespace Modules\Inspection\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InspectionCharge extends Model
{
    use HasFactory;

    protected $fillable = [
        'inspection_id',
        'order_no',
        'service_description',
        'qty',
        'unit_price',
        'total',
    ];

    protected function casts(): array
    {
        return [
            'qty' => 'decimal:2',
            'unit_price' => 'decimal:2',
            'total' => 'decimal:2',
        ];
    }

    public function inspection(): BelongsTo
    {
        return $this->belongsTo(Inspection::class);
    }
}
