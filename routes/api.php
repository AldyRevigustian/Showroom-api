<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\LiveController;
use App\Http\Controllers\API\ProfileController;
use App\Http\Controllers\API\RoomController;
use App\Http\Controllers\FarmingController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        "message" => "Welcome To SHOWROOM API",
        "home" => 'https://www.jkt48-showroom.com/',
        "repository" => "https://github.com/AldyRevigustian/Showroom-Api"
    ]);
});
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::prefix('live')->controller(LiveController::class)->group(function () {
    Route::post('/comment', 'send_comment');
    Route::post('/send_gift', 'send_gift');
    Route::post('/bulk_gift', 'bulk_gift');
});

Route::prefix('room')->controller(RoomController::class)->group(function () {
    Route::post('/followed_rooms', 'followed_rooms');
    Route::post('/follow', 'follow');
});

Route::prefix('profile')->controller(ProfileController::class)->group(function () {
    Route::post('/user', 'profile');
    Route::post('/detail', 'detail');
    Route::post('/update', 'update_profile');
    Route::post('/update_avatar', 'update_avatar');
    Route::post('/get_avatar', 'get_avatar');
    Route::post('/room', 'room');
});

Route::get('/room_official', [FarmingController::class, 'room_official']);
Route::post('/farming', [FarmingController::class, 'farming']);
