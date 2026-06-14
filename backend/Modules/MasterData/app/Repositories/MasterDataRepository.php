<?php

namespace Modules\MasterData\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Modules\MasterData\Models\Allocation;
use Modules\MasterData\Models\Condition;
use Modules\MasterData\Models\Customer;
use Modules\MasterData\Models\InspectionParameter;
use Modules\MasterData\Models\InspectionType;
use Modules\MasterData\Models\ItemCategory;
use Modules\MasterData\Models\Location;
use Modules\MasterData\Models\Owner;
use Modules\MasterData\Models\ScopeOfWork;

class MasterDataRepository
{
    public function itemCategories(): Collection
    {
        return ItemCategory::orderBy('name')->get();
    }

    public function inspectionTypes(): Collection
    {
        return InspectionType::orderBy('name')->get();
    }

    public function inspectionParameters(): Collection
    {
        return InspectionParameter::orderBy('name')->get();
    }

    public function scopesOfWork(): Collection
    {
        return ScopeOfWork::with('parameters')->orderBy('name')->get();
    }

    public function locations(): Collection
    {
        return Location::orderBy('name')->get();
    }

    public function customers(): Collection
    {
        return Customer::orderBy('name')->get();
    }

    public function conditions(): Collection
    {
        return Condition::orderBy('name')->get();
    }

    public function owners(): Collection
    {
        return Owner::orderBy('name')->get();
    }

    public function allocations(): Collection
    {
        return Allocation::orderBy('name')->get();
    }
}
