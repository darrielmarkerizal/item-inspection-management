<?php

namespace Modules\Inspection\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\Core\Http\Responses\ApiResponse;
use Modules\Inspection\Http\Requests\ListInspectionRequest;
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
}
