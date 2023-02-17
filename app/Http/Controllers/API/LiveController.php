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
        return ['live_id' => $resBod->live_id, 'room_url_key' => $resBod->room_url_key];
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

    public function send_gift(Request $request)
    {
        $client = new Client();
        $csrf_token = $request->csrf_token;
        $cookies_id = $request->cookies_id;

        $gift_id = $request->gift_id;
        $live_id = $request->live_id;
        $num = $request->num;
        $is_delay = 0;

        $send_gift = $client->post('https://www.showroom-live.com/api/live/gifting_free', [
            'headers' => [
                'Cookie' => $cookies_id,
            ],
            'form_params' => [
                'csrf_token' => $csrf_token,
                'gift_id' => $gift_id,
                'live_id' => $live_id,
                'num' => $num,
                'is_delay' => $is_delay,
            ],
        ]);

        if ($send_gift->getStatusCode() == '200') {
            $sendGiftJson = json_decode($send_gift->getBody()->getContents());

            if (isset($sendGiftJson->ok)) {
                return response()->json(
                    $sendGiftJson
                );
            }
            return response()->json(
                [
                    'message' => 'Gagal Send Gift'
                ]
            );
        }
    }
    public function bulk_gift(Request $request)
    {
        $client = new Client();
        $csrf_token = $request->csrf_token;
        $cookies_id = $request->cookies_id;

        $live_id = $request->live_id;

        $bulk_gift = $client->post('https://www.showroom-live.com/api/live/bulk_gifting_free', [
            'headers' => [
                'Cookie' => $cookies_id,
            ],
            'form_params' => [
                'csrf_token' => $csrf_token,
                'live_id' => $live_id,
            ],
        ]);

        if ($bulk_gift->getStatusCode() == '200') {
            $bulkGiftJson = json_decode($bulk_gift->getBody()->getContents());

            if (isset($bulkGiftJson->ok)) {
                return response()->json(
                    $bulkGiftJson
                );
            }
            return response()->json(
                [
                    'message' => 'Gagal Send Bulk Gift'
                ]
            );
        }
    }
}
