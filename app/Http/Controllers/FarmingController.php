<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class FarmingController extends Controller
{
    public function room_official()
    {
        $res = Http::get("https://www.showroom-live.com/api/live/onlives");
        $resBod = json_decode($res->body());

        $onlives = [];
        foreach ($resBod->onlives as $live) {
            foreach ($live->lives as $official) {
                if ($official->official_lv == 1) {
                    $room[] = $official->room_id;
                    $l['room'] = $room;
                }
            }
            $onlives[] = $l['room'];
        }

        $room_id = array_unique(end($onlives));

        if (count($room_id) > 50) {
            return response()->json([
                'room_id' => array_slice($room_id, 0, 50)
            ]);
        }
        return response()->json([
            'room_id' => $room_id
        ]);
    }

    public function farming(Request $request)
    {
        $client = new Client();
        $cookies_id = $request->cookies_login_id;
        $room_id = $request->room_id;

        $getStar = $client->get("https://www.showroom-live.com/api/live/polling?room_id={$room_id}", [
            'headers' => [
                'Cookie' => $cookies_id,
            ],
        ]);
        if ($getStar->getStatusCode() == '200') {
            $data = json_decode($getStar->getBody()->getContents());

            if (isset($data->live_end)) {
                return response()->json([
                    'message' => "[{$room_id}] Offline",
                ]);
            }

            if (isset($data->toast->image)) {
                return response()->json([
                    'message' => "[{$room_id}] Sukses Melakukan farming",
                    'data' => $data
                ]);
            }

            if (isset($data->live_watch_incentive->error)) {
                return response()->json([
                    'message' => "[{$room_id}] Gagal Melakukan farming",
                    'until' => $data->live_watch_incentive->message,
                    'data' => $data
                ]);
            }

            return response()->json([
                'message' => "[{$room_id}] Sedang Melakukan farming",
                'data' => $data
            ]);
        }
    }
}
