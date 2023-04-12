<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        "message" => "Welcome To SHOWROOM API",
        "home" => 'https://www.jkt48-showroom.com/',
        "repository" => "https://github.com/AldyRevigustian/Showroom-Api"
    ]);
});
