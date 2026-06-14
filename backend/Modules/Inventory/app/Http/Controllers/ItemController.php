<?php

namespace Modules\Inventory\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\Core\Http\Responses\ApiResponse;
use Modules\Inventory\Services\ItemService;

class ItemController extends Controller
{
    public function __construct(private readonly ItemService $service) {}

    public function index(): JsonResponse
    {
        return ApiResponse::success($this->service->all(), 'Items retrieved');
    }
}
