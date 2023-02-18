<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function update_profile(Request $request)
    {
        $cookies_id = $request->cookies_id;
        $csrf_token = $request->csrf_token;

        $name = $request->name;
        $user_id = $request->user_id;
        $residence = $request->residence;
        $description = $request->description;

        $client = new Client();

        $update = $client->post('https://www.showroom-live.com/api/user/detail/update', [
            'headers' => [
                'Cookie' => $cookies_id,
            ],
            'form_params' => [
                'csrf_token' => $csrf_token,
                'name' => $name,
                'user_id' => $user_id,
                'residence' => $residence,
                'description' => $description,
            ],
        ]);

        if ($update->getStatusCode() == '200') {

            $updateJson = json_decode($update->getBody()->getContents());

            if (isset($updateJson->ok)) {
                return response()->json(
                    [
                        'message' => 'Berhasil Mengupdate Profile'
                        ]
                    );
                }

                return response()->json(
                    [
                        'message' => $updateJson->error_user_msg
                ]
            );
        }
    }

    public function get_avatar(Request $request){
        $client = new Client();
        $cookies_id = $request->cookies_id;
        $offset = $request->offset;
        $limit = $request->limit;
        $type = $request->type;

        $avatars = $client->get("https://www.showroom-live.com/api/user_avatar?offset={$offset}&limit={$limit}&type={$type}", [
            'headers' => [
                'Cookie' => $cookies_id,
            ],
        ]);

        $avatarsJson = json_decode($avatars->getBody()->getContents());

        return response()->json(
            $avatarsJson
        );
    }

    public function update_avatar(Request $request)
    {
        $cookies_id = $request->cookies_id;
        $csrf_token = $request->csrf_token;
        $avatar_id = $request->avatar_id;

        $client = new Client();
        $update = $client->post('https://www.showroom-live.com/api/user/update_user_avatar', [
            'headers' => [
                'Cookie' => $cookies_id,
            ],
            'form_params' => [
                'csrf_token' => $csrf_token,
                'avatar_id' => $avatar_id,
            ],
        ]);

        if ($update->getStatusCode() == '200') {
            $updateJson = json_decode($update->getBody()->getContents());

            if (isset($updateJson->ok)) {
                return response()->json(
                    [
                        'message' => 'Berhasil Mengupdate Avatar'
                    ]
                );
            }
            return response()->json(
                [
                    'message' => 'Gagal Mengupdate Avatar'
                ]
            );
        }
    }
}
