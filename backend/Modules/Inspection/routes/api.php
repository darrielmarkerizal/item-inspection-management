<?php

use Illuminate\Support\Facades\Route;
use Modules\Inspection\Http\Controllers\InspectionController;

Route::prefix('v1')->group(function () {
    Route::get('inspections', [InspectionController::class, 'index'])->name('inspections.index');
    Route::post('inspections', [InspectionController::class, 'store'])->name('inspections.store');
    Route::get('inspections/{inspection}', [InspectionController::class, 'show'])->name('inspections.show');
    Route::put('inspections/{inspection}', [InspectionController::class, 'update'])->name('inspections.update');
    Route::patch('inspections/{inspection}/status', [InspectionController::class, 'updateStatus'])->name('inspections.status');
});
