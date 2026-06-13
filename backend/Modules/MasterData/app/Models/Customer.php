<?php

namespace Modules\MasterData\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Inspection\Models\Inspection;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
    ];

    public function inspections(): HasMany
    {
        return $this->hasMany(Inspection::class);
    }
}
