<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\LiveController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);

Route::prefix('live')->controller(LiveController::class)->group(function () {
    Route::post('/comment', 'send_comment');
});

// base64:M06aohn+JosU2cQ0hxa/zf0WE9YDUeK3daREE5xaQ3c=
