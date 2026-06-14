<?php

namespace Modules\MasterData\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\Core\Http\Responses\ApiResponse;
use Modules\MasterData\Services\MasterDataService;

class MasterDataController extends Controller
{
    public function __construct(private readonly MasterDataService $service) {}

    public function index(): JsonResponse
    {
        return ApiResponse::success($this->service->all(), 'Master data retrieved');
    }
}
