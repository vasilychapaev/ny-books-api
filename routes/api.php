<?php

use App\Http\Controllers\Api\V1\NytBooksApiController;
use Illuminate\Support\Facades\Route;

Route::name('api.')->prefix('v1')->group(function () {
    Route::get('/best-sellers/history', [NytBooksApiController::class, 'history'])->name('history');
    Route::get('/lists', [NytBooksApiController::class, 'lists'])->name('lists');
}); 