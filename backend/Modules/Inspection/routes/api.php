<?php

use Illuminate\Support\Facades\Route;
use Modules\Inspection\Http\Controllers\InspectionController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('inspections', InspectionController::class)->names('inspection');
});
