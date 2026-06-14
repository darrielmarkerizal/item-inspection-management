<?php

namespace Modules\Inspection\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\Core\Http\Responses\ApiResponse;
use Modules\Inspection\Http\Requests\ListInspectionRequest;
use Modules\Inspection\Http\Requests\StoreInspectionRequest;
use Modules\Inspection\Http\Requests\UpdateInspectionRequest;
use Modules\Inspection\Http\Requests\UpdateInspectionStatusRequest;
use Modules\Inspection\Http\Resources\InspectionResource;
use Modules\Inspection\Services\InspectionService;

class InspectionController extends Controller
{
    public function __construct(private readonly InspectionService $service) {}

    public function index(ListInspectionRequest $request): JsonResponse
    {
        return ApiResponse::success(
            $this->service->list($request->validated()),
            'Inspections retrieved'
        );
    }

    public function store(StoreInspectionRequest $request): JsonResponse
    {
        return ApiResponse::created(
            new InspectionResource($this->service->create($request->validated())),
            'Inspection created'
        );
    }

    public function show(int|string $inspection): JsonResponse
    {
        return ApiResponse::success(
            new InspectionResource($this->service->find($inspection)),
            'Inspection retrieved'
        );
    }

    public function update(UpdateInspectionRequest $request, int|string $inspection): JsonResponse
    {
        return ApiResponse::success(
            new InspectionResource($this->service->update($inspection, $request->validated())),
            'Inspection updated'
        );
    }

    public function updateStatus(UpdateInspectionStatusRequest $request, int|string $inspection): JsonResponse
    {
        return ApiResponse::success(
            new InspectionResource($this->service->updateStatus($inspection, $request->validated('status'))),
            'Inspection status updated'
        );
    }
}
