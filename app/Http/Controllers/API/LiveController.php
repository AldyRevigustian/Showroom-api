<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\MultipartStream;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class LiveController extends Controller
{
    function visitRoom($cookie_id, $room_url_key)
    {
        $client = new Client();
        $profile = $client->get(
            "https://www.showroom-live.com/r/{$room_url_key}",
            [
                'headers' => ['Cookie' => $cookie_id]
            ]
        );
        return true;
    }

    function getDetail($room_id)
    {
        $res = Http::get("https://www.showroom-live.com/api/room/profile?room_id={$room_id}");
        $resBod = json_decode($res->body());
        return ['live_id' =>$resBod->live_id, 'room_url_key' => $resBod->room_url_key ];
    }

    public function send_comment(Request $request)
    {
        $client = new Client();

        $room_detail = $this->getDetail($request->room_id);

        $live_id = $room_detail['live_id'];
        $room_url_key = $room_detail['room_url_key'];

        $csrf = $request->csrf;
        $cookies_id = $request->cookies_id;
        $comment = $request->comment;

        $this->visitRoom($cookies_id, $room_url_key);
        $boundary = '----WebKitFormBoundarydMIgtiA2YeB1Z0kl';

        $multipart_form = [
            [
                'name' => 'live_id',
                'contents' => $live_id,
            ],
            [
                'name' => 'comment',
                'contents' => $comment,
            ],
            [
                'name' => 'csrf_token',
                'contents' => $csrf,
            ],
        ];

        $comment = $client->post('https://www.showroom-live.com/api/live/post_live_comment', [
            'headers' => [
                'Content-Type' => 'multipart/form-data; boundary=' . $boundary,
                'Host' => 'www.showroom-live.com',
                'Cookie' => $cookies_id,
                'Content-Length' => 380,
            ],

            'body' => new MultipartStream($multipart_form, $boundary), // here is all the magic
        ]);


        return response()->json(
            json_decode($comment->getBody()->getContents())
        );
    }

    public function comment_log($room_id)
    {
        $res = Http::get("https://www.showroom-live.com/api/live/comment_log?room_id={$room_id}");
        $resBod = json_decode($res->body());

        return response()->json(
            $resBod
        );
    }

    public function gift_log($room_id)
    {
        $res = Http::get("https://www.showroom-live.com/api/live/gift_log?room_id={$room_id}");
        $resBod = json_decode($res->body());

        return response()->json(
            $resBod
        );
    }

    public function stage_user_list($room_id)
    {
        $res = Http::get("https://www.showroom-live.com/api/live/stage_user_list?room_id={$room_id}");
        $resBod = json_decode($res->body());

        return response()->json(
            $resBod
        );
    }
}
