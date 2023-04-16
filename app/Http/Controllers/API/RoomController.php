<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\MultipartStream;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class RoomController extends Controller
{
    public function followed_rooms(Request $request)
    {
        $client = new Client();
        $cookies_id = $request->cookies_id;

        $followed = $client->get("https://www.showroom-live.com/api/follow/rooms", [
            'headers' => [
                'Cookie' => $cookies_id,
            ],
        ]);

        $followedJson = json_decode($followed->getBody()->getContents());

        return response()->json(
            $followedJson
        );
    }

    public function follow(Request $request)
    {
        $client = new Client();
        $cookies_id = $request->cookies_id;
        $room_id = $request->room_id;
        $csrf_token = $request->csrf_token;
        $flag = $request->flag;

        $followed = $client->post("https://www.showroom-live.com/api/room/follow", [
            'headers' => [
                'Cookie' => $cookies_id,
            ],
            'form_params' => [
                'room_id' => $room_id,
                'csrf_token' => $csrf_token,
                'flag' => $flag,
            ],
        ]);

        $followedJson = json_decode($followed->getBody()->getContents());

        return response()->json(
            $followedJson
        );
    }
}
