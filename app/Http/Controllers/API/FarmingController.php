<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

use function PHPUnit\Framework\isEmpty;

class FarmingController extends Controller
{
    function detail_star($room_id, $cookies_id)
    {
        $client = new Client();
        $detailStar = $client->get("https://www.showroom-live.com/api/live/current_user?room_id={$room_id}", [
            'headers' => [
                'Cookie' => $cookies_id,
            ],
        ]);

        return json_decode($detailStar->getBody()->getContents());
    }

    public function get_room()
    {
        $res = Http::get("https://www.showroom-live.com/api/live/onlives");
        $resBod = json_decode($res->body());

        $onlives = [];
        foreach ($resBod->onlives as $live) {
            foreach ($live->lives as $official) {
                if (isset($official->official_lv)) {
                    if ($official->official_lv == 1) {
                        $room = $official->room_id;
                        $roomName = $official->main_name;
                        $l['room_id'] = $room;
                        $l['room_name'] = $roomName;
                        $onlives[] = $l;
                    }
                }
            }
        }

        $room_id = collect($onlives)->unique('room_id')->all();

        if (count($room_id) > 50) {
            return response()->json(
                array_slice($room_id, 0, 50)
            );
        }
        return response()->json(
            $room_id
        );
    }

    public function start(Request $request)
    {
        $client = new Client();
        $cookies_id = $request->cookies_login_id;
        $room_id = $request->room_id;
        $room_name = $request->room_name;

        $getStar = $client->get("https://www.showroom-live.com/api/live/polling?room_id={$room_id}", [
            'headers' => [
                'Cookie' => $cookies_id,
            ],
        ]);
        if ($getStar->getStatusCode() == '200') {
            $data = json_decode($getStar->getBody()->getContents());
            $star = $this->detail_star($room_id, $cookies_id);

            if (isset($data->live_end)) {
                return response()->json([
                    'message' => "[{$room_name}] Offline",
                    'star' => isset($star->gift_list->normal)
                ]);
            }

            if (isset($data->toast->image)) {
                return response()->json([
                    'message' => "[{$room_name}] Sukses Melakukan farming",
                    'data' => $data,
                    'star' => isset($star->gift_list->normal) ? $star->gift_list->normal : ''
                ]);
            }

            if (isset($data->live_watch_incentive->error)) {
                return response()->json([
                    'message' => "[{$room_name}] Gagal Melakukan farming",
                    'until' => $data->live_watch_incentive->message,
                    'data' => $data,
                    'star' => isset($star->gift_list->normal) ? $star->gift_list->normal : ''
                ]);
            }

            return response()->json([
                'message' => "[{$room_name}] Sedang Melakukan farming",
                'data' => $data,
                'star' => isset($star->gift_list->normal) ? $star->gift_list->normal : ''
            ]);
        }
    }
}
