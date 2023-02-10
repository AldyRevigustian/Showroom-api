<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\LiveController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);

Route::prefix('live')->controller(LiveController::class)->group(function () {
    Route::post('/comment', 'send_comment');
});
