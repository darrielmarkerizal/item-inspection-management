<?php

namespace Modules\MasterData\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class InspectionParameter extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'description',
    ];

    public function scopesOfWork(): BelongsToMany
    {
        return $this->belongsToMany(ScopeOfWork::class, 'scope_parameter');
    }
}
