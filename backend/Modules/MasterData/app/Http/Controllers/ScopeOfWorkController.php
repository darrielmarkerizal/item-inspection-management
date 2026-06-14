<?php

namespace Modules\MasterData\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\Core\Http\Responses\ApiResponse;
use Modules\MasterData\Http\Requests\StoreScopeOfWorkRequest;
use Modules\MasterData\Http\Resources\ScopeOfWorkResource;
use Modules\MasterData\Services\ScopeOfWorkService;

class ScopeOfWorkController extends Controller
{
    public function __construct(private readonly ScopeOfWorkService $service) {}

    public function store(StoreScopeOfWorkRequest $request): JsonResponse
    {
        $scope = $this->service->create($request->validated());

        return ApiResponse::created(new ScopeOfWorkResource($scope), 'Scope of work created');
    }
}
