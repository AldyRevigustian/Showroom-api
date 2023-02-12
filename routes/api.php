<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\LiveController;
use App\Http\Controllers\FarmingController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        "message" => "Welcome To JKT48 SHOWROOM API",
        "home" => 'https://www.jkt48-showroom.com/',
        "repository" => "https://github.com/AldyRevigustian/Showroom-Api"
    ]);
});
Route::post('/login', [AuthController::class, 'login']);

Route::prefix('live')->controller(LiveController::class)->group(function () {
    Route::post('/comment', 'send_comment');
});

Route::get('/room_official', [FarmingController::class, 'room_official']);
Route::post('/farming', [FarmingController::class, 'farming']);
