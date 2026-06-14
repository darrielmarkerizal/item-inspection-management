<?php

namespace Modules\MasterData\Services;

use Modules\Core\Enums\InspectionStatus;
use Modules\Core\Enums\ServiceType;
use Modules\MasterData\Http\Resources\AllocationResource;
use Modules\MasterData\Http\Resources\ConditionResource;
use Modules\MasterData\Http\Resources\CustomerResource;
use Modules\MasterData\Http\Resources\InspectionParameterResource;
use Modules\MasterData\Http\Resources\InspectionTypeResource;
use Modules\MasterData\Http\Resources\ItemCategoryResource;
use Modules\MasterData\Http\Resources\LocationResource;
use Modules\MasterData\Http\Resources\OwnerResource;
use Modules\MasterData\Http\Resources\ScopeOfWorkResource;
use Modules\MasterData\Repositories\MasterDataRepository;

class MasterDataService
{
    public function __construct(private readonly MasterDataRepository $repository) {}

    public function all(): array
    {
        return [
            'service_types' => ServiceType::options(),
            'statuses' => InspectionStatus::options(),
            'item_categories' => ItemCategoryResource::collection($this->repository->itemCategories()),
            'inspection_types' => InspectionTypeResource::collection($this->repository->inspectionTypes()),
            'inspection_parameters' => InspectionParameterResource::collection($this->repository->inspectionParameters()),
            'scopes_of_work' => ScopeOfWorkResource::collection($this->repository->scopesOfWork()),
            'locations' => LocationResource::collection($this->repository->locations()),
            'customers' => CustomerResource::collection($this->repository->customers()),
            'conditions' => ConditionResource::collection($this->repository->conditions()),
            'owners' => OwnerResource::collection($this->repository->owners()),
            'allocations' => AllocationResource::collection($this->repository->allocations()),
        ];
    }
}
