<?php

use Illuminate\Support\Facades\Route;
use Modules\MasterData\Http\Controllers\MasterDataController;
use Modules\MasterData\Http\Controllers\ScopeOfWorkController;

Route::prefix('v1')->group(function () {
    Route::get('master-data', [MasterDataController::class, 'index'])->name('master-data');
    Route::post('scopes-of-work', [ScopeOfWorkController::class, 'store'])->name('scopes-of-work.store');
});
