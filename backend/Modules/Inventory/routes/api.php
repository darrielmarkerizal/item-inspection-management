<?php

use Illuminate\Support\Facades\Route;
use Modules\Inventory\Http\Controllers\ItemController;

Route::prefix('v1')->group(function () {
    Route::get('items', [ItemController::class, 'index'])->name('items');
});
