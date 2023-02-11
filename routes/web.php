<?php

use App\Http\Controllers\FetchAllLive;
use App\Http\Controllers\FetchAllRoom;
use Illuminate\Support\Facades\Route;
use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return response()->json([
        "message" => "Welcome To JKT48 SHOWROOM API",
        "repository" => "https://github.com/AldyRevigustian/Showroom-Api"
    ]);
});

// Route::post('/', function (Request $request) {
//     dd($request->all());
//     // Htt
//     // $jar = new CookieJar();

//     // $client = new Client([
//     //     'cookies' => $jar,
//     // ]);

//     // // $response = $client->get('https://api.example.com/data');

//     // echo $client->;
//     // // return view('tes');
// })->name('tes');
