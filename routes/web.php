<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('https://laravel-showroom-api.vercel.app/api');
});
