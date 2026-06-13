<?php

use Illuminate\Support\Facades\Route;
use Modules\Inspection\Http\Controllers\InspectionController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('inspections', InspectionController::class)->names('inspection');
});
