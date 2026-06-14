<?php

use Illuminate\Support\Facades\Route;
use Modules\MasterData\Http\Controllers\MasterDataController;

Route::prefix('v1')->group(function () {
    Route::get('master-data', [MasterDataController::class, 'index'])->name('master-data');
});
