<?php

use Illuminate\Support\Facades\Route;
use Modules\Inspection\Http\Controllers\InspectionController;

Route::prefix('v1')->group(function () {
    Route::get('inspections', [InspectionController::class, 'index'])->name('inspections.index');
});
